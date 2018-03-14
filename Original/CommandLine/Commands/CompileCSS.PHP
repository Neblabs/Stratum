<?php

namespace Stratum\Original\CommandLine\Command;

use Stratum\CoreBox\CssManagement\CssCompiler;
use Stratum\CoreBox\CssManagement\CssFileRegistratorManager;
use Stratum\CoreBox\CssManagement\PHPCompiler;
use Stratum\CoreBox\GroupofSettings;
use Stratum\Custom\Component\Stylesheets;
use Stratum\Custom\Findl\MYSQL\Settings;
use Stratum\Original\Presentation\ElementManagersQueue;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

Class CompileCSS extends Command
{
    

    protected function configure()
    {
        $this->setName('compileCSS');
        $this->setDescription('Compiles dynamic pseudo stylesheets into php strings and or css files.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        (object) $find = new Finder;

        $find->files()->name('*.css')->in($this->stylesheetsDirectory());

        foreach ($find as $file) {
            (string) $filePath = $file->getRelativePathname();

            (object) $CSSPHPCompiler = new PHPCompiler($filePath);
            $CSSPHPCompiler->compile();
            

            (object) $CssCompiler = new CssCompiler($filePath, $this->data());
            $CssCompiler->compile();


        }

        $this->mergeAndCompileAllFilesIntoOne();
    }

    protected function stylesheetsDirectory()
    {
        return STRATUM_ROOT_DIRECTORY . '/Design/Present/Stylesheets';
    }

    protected function data()
    {
        (object) $header = new \Stdclass;
        $header->height = '';
        $header->barColor = '';
        $header->textColor = '';
        $header->fontWeight = '';
        $header->navigationFontWeight = '';

        (object) $logoAndBurger = new \Stdclass;
        $logoAndBurger->boxColor = '';

        (object) $settings = new Settings;
        
        return [
            'header' => $header,
            'logoAndBurger' => $logoAndBurger,
            'setting' => $settings
        ];
    }


    protected function mergeAndCompileAllFilesIntoOne()
    {

        (object) $stylesheetElements = (new Stylesheets([
            'isDevelopmentEnvironment' => true,
            'isProductionEnvironment' => false,
            'siteUrl' => Options::withName('siteurl')->find()->first()->value
        ]))->elements();
        (object) $elementManagersQueue = new ElementManagersQueue;

        $elementManagersQueue->executeManagerTasks();

        $this->resetPHPFile();

        foreach ($stylesheetElements as $stylesheetElement) {

            (string) $developmentSourceFileName = $this->getDevelopmentSourceFileFrom($stylesheetElement->href);

            (object) $CssFileRegistratorManager = new CssFileRegistratorManager($developmentSourceFileName);
            (string) $compiledPHPFilePath = $this->compiledPHPPathFor($CssFileRegistratorManager->PHPCompiledFileName()); 

            $this->appendMasterPHPFile($compiledPHPFilePath);  


        }

        $this->cleanPHPFile();  

        (object) $CssCompiler = new CssCompiler('Main/Master.css', $this->data());

        $CssCompiler->compile();

    }

    protected function getDevelopmentSourceFileFrom($compiledCSSFileName)
    {

        (string) $fileNameWithoutAbsoluteUrl = str_replace($this->absoluteUrlForStylesheets(), '', $compiledCSSFileName);

        return $this->filenameWithoutNumbers($fileNameWithoutAbsoluteUrl);

    }

    protected function absoluteUrlForStylesheets()
    {
        (string) $siteUrl = Options::withName('siteurl')->find()->first()->value;

        return [
            "{$siteUrl}/wp-content/themes/Corebox/Storage/CompiledCss/",
            "{$siteUrl}/wp-content/themes/Corebox/Storage/CompiledCss/",
        ];
    }

    protected function filenameWithoutNumbers($fileNameWithoutAbsoluteUrl)
    {
        return preg_replace('/[0-9]/', '', $fileNameWithoutAbsoluteUrl);
    }

    protected function compiledPHPPathFor($compiledCSSFileName)
    {
        return STRATUM_ROOT_DIRECTORY . "/Storage/CompiledPHPCSS/{$compiledCSSFileName}";
    }

    protected function appendMasterPHPFile($compiledCssFilePath)
    {   
        (string) $mainFileName = STRATUM_ROOT_DIRECTORY. '/Storage/CompiledPHPCSS/Master/Master.php';

        file_put_contents($mainFileName, file_get_contents($compiledCssFilePath), FILE_APPEND);
    }

    protected function cleanPHPFile()
    {
        (string) $mainFileName = STRATUM_ROOT_DIRECTORY. '/Storage/CompiledPHPCSS/Master/Master.php';

        (string) $mainFileNameWithNoOpenningPHPTags = str_replace('<?php', '', file_get_contents($mainFileName));
        (string) $mainFileWithNoNamespaceDeclarations = str_replace('; 
            use Stratum\CoreBox\CssManagement\PropertyValueCompiler;

            return ', ' . ', $mainFileNameWithNoOpenningPHPTags);
        (string) $mainFileWithOpennigTag = '<?php  ' . $mainFileWithNoNamespaceDeclarations;


        file_put_contents($mainFileName, $mainFileWithOpennigTag);
    }

    protected function resetPHPFile()
    {
        (string) $mainFileName = STRATUM_ROOT_DIRECTORY. '/Storage/CompiledPHPCSS/Master/Master.php';

        file_put_contents($mainFileName, '');
    }

}



