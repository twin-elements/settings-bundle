<?php

namespace TwinElements\SettingsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TwinElements\AdminBundle\Helper\Breadcrumbs;
use TwinElements\AdminBundle\Helper\TranslationsManager;
use TwinElements\AdminBundle\Model\CrudControllerTrait;
use TwinElements\Component\AdminTranslator\AdminTranslator;
use TwinElements\Component\CrudLogger\CrudLoggerInterface;
use TwinElements\Component\Flashes\Flashes;
use TwinElements\SettingsBundle\Form\SettingsUploadsType;

/**
 * @Route("/settings")
 */
class SettingsController extends AbstractController
{
    use CrudControllerTrait {
        CrudControllerTrait::__construct as private __crudConstruct;
    }

    /**
     * @var TranslationsManager $translationsManager
     */
    private $translationsManager;

    public function __construct(
        Breadcrumbs         $breadcrumbs,
        Flashes             $flashes,
        CrudLoggerInterface   $crudLogger,
        AdminTranslator     $translator,
        TranslationsManager $translationsManager)
    {
        $this->__crudConstruct($breadcrumbs, $flashes, $crudLogger, $translator);
        $this->translationsManager = $translationsManager;
    }

    /**
     * @Route("/", name="settings_index", methods={"GET"})
     */
    public function index(
        Breadcrumbs $breadcrumbs
    )
    {
        // wersję językowe
        $breadcrumbs->addItem($this->adminTranslator->translate('admin_settings.settings'));

        return $this->render('@TwinElementsSettings/index.html.twig', [

        ]);
    }

//    /**
//     * @Route("/basic-data", name="basic_data", methods={"GET"})
//     */
//    public function basicData()
//    {
//
//    }
//
//    /**
//     * @Route("/codes", name="codes", methods={"GET"})
//     */
//    public function codes()
//    {
//
//    }

    /**
     * @Route("/uploads", name="uploads", methods={"GET", "POST"})
     */
    public function uploads(
        Request $request
    )
    {
        $form = $this->createForm(SettingsUploadsType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $uploadedData = [];
                $uploadsPath = $request->server->get('DOCUMENT_ROOT');

                /** @var UploadedFile $logoFile */
                $logoFile = $form->get('logo')->getData();
                if ($logoFile) {
                    $safeFilename = 'logo';
                    $logoFilename = $safeFilename . '.' . $logoFile->guessExtension();

                    $logoFile->move($uploadsPath, $logoFilename);
                    $uploadedData['logo'] = $logoFilename;
                }

                /** @var UploadedFile $sitemapFile */
                $sitemapFile = $form->get('sitemap')->getData();
                if ($sitemapFile) {
                    $safeFilename = 'sitemap';
                    $sitemapFilename = $safeFilename . '.' . $sitemapFile->guessExtension();

                    $sitemapFile->move($uploadsPath, $sitemapFilename);
                    $uploadedData['sitemap'] = $sitemapFilename;
                }

                if (count($uploadedData) > 0) {
                    if (!file_exists($this->translationsManager->getCatalogPath('uploads', $request->getDefaultLocale()))) {
                        $this->translationsManager->createCatalog('uploads', $request->getDefaultLocale());
                    }

                    foreach ($uploadedData as $key => $value) {
                        $this->translationsManager->updateKeyTranslations('uploads.' . $key, 'uploads', [
                            $request->getDefaultLocale() => $value
                        ]);
                    }
                }

            } catch (FileException $exception) {
                $this->flashes->errorMessage($exception->getMessage());
            }

            return $this->redirectToRoute('uploads');
        }

        $this->breadcrumbs
            ->addItem($this->adminTranslator->translate('admin_settings.settings'), $this->generateUrl('settings_index'))
            ->addItem($this->adminTranslator->translate('admin_settings.uploads_module_name'));

        return $this->render('@TwinElementsSettings/uploads/uploads.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
