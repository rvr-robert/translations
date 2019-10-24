<?php
namespace Custom\Translations\Model;

use Magento\Framework\App\Filesystem\DirectoryList;

class FileManager extends \Magento\Translation\Model\FileManager
{
    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    private $assetRepo;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $driverFile;

    public function __construct(
        \Magento\Framework\View\Asset\Repository $assetRepo,
        DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Driver\File $driverFile
    )
    {
        $this->assetRepo = $assetRepo;
        $this->directoryList = $directoryList;
        $this->driverFile = $driverFile;

        parent::__construct(
            $assetRepo,
            $directoryList,
            $driverFile
        );
    }

    /**
     * @param string $content
     * @param string $storeCode
     * @return void
     */
    public function updateTranslationFileContent($content, $storeCode = null)
    {
        if($storeCode != null) {
            $fullpath = $this->getTranslationFileFullPath();
            $fullpath = explode('/', $fullpath);
            $fullpath[count($fullpath)-2] = $storeCode;
            $fullpath[count($fullpath)-5] = 'frontend';
            $fullpath = implode("/", $fullpath);
            $translationDir = $this->directoryList->getPath(DirectoryList::STATIC_VIEW) .
                \DIRECTORY_SEPARATOR .
                $this->assetRepo->getStaticViewFileContext()->getPath();
            $translationDir = explode('/', $translationDir);
            $translationDir[count($translationDir)-1] = $storeCode;
            $translationDir[count($translationDir)-4] = 'frontend';
            $translationDir = implode("/", $translationDir);
            if (!$this->driverFile->isExists($fullpath)) {
                $this->driverFile->createDirectory($translationDir);
            }
            $this->driverFile->filePutContents($fullpath, $content);
        } else {
            $translationDir = $this->directoryList->getPath(DirectoryList::STATIC_VIEW) .
                \DIRECTORY_SEPARATOR .
                $this->assetRepo->getStaticViewFileContext()->getPath();
            if (!$this->driverFile->isExists($this->getTranslationFileFullPath())) {
                $this->driverFile->createDirectory($translationDir);
            }
            $this->driverFile->filePutContents($this->getTranslationFileFullPath(), $content);
        }
    }
}