<?php

namespace Stratum\Original\CommandLine\Command;

use Stratum\Custom\Finder\MYSQL\AdBlocks;
use Stratum\Custom\Finder\MYSQL\CommentMeta;
use Stratum\Custom\Finder\MYSQL\Comments;
use Stratum\Custom\Finder\MYSQL\Options;
use Stratum\Custom\Finder\MYSQL\PostBlocks;
use Stratum\Custom\Finder\MYSQL\PostMeta;
use Stratum\Custom\Finder\MYSQL\Posts;
use Stratum\Custom\Finder\MYSQL\Settings;
use Stratum\Custom\Finder\MYSQL\Taxonomies;
use Stratum\Custom\Finder\MYSQL\TermRelationships;
use Stratum\Custom\Finder\MYSQL\Terms;
use Stratum\Custom\Finder\MYSQL\UserMeta;
use Stratum\Custom\Finder\MYSQL\Users;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

Class ExportStyle extends Command
{
    protected $styleName = 'original';

    protected function configure()
    {
        $this->setName('exportStyle');
        $this->setDescription('');
        $this->addArgument('styleName', InputArgument::REQUIRED, 'The name of the style being exported');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->styleName = $input->getArgument('styleName');
        $this->exportStyles();
        $this->exportDemoContent();
        $this->copyStyleToMasterTheme();
    }

    private function exportStyles()
    {
        $this->exportFullSettingsIfIsDefault();

        (object) $settingsCSVExporter = Settings::createExporter('csv');
        (object) $postBlocksCSVExporter = PostBlocks::createExporter('csv');
        (object) $adsCSVExporter = AdBlocks::createExporter('csv');

        (object) $settingsSQLExporter = Settings::createExporter('sql');
        (object) $postBlocksSQLExporter = PostBlocks::createExporter('sql');
        (object) $adsSQLExporter = AdBlocks::createExporter('sql');



        $settingsCSVExporter->exportToFile($this->stylesDirectory() . '/Corebox-settings.csv');
        $postBlocksCSVExporter->exportToFile($this->stylesDirectory() . '/Corebox-post-blocks.csv');
        $adsCSVExporter->exportToFile($this->stylesDirectory() . '/Corebox-ads.csv');


        $settingsSQLExporter->exportToFile($this->stylesDirectory() . '/Corebox-settings.sql');
        $postBlocksSQLExporter->exportToFile($this->stylesDirectory() . '/Corebox-post-blocks.sql');
        $adsSQLExporter->exportToFile($this->stylesDirectory() . '/Corebox-ads.sql');
    }

    private function exportFullSettingsIfIsDefault()
    {
        (string) $isNotTheDefaultStyle = !(strtolower($this->styleName) === 'default');

        if ($isNotTheDefaultStyle) { return; }

        (object) $settingsCSVExporter = Settings::fullSettingsExporter('csv');

        $settingsCSVExporter->exportToFile($this->stylesDirectory() . '/Complete/Corebox-settings.csv');
    }

    private function exportDemoContent()
    {
        (string) $demoContentDirectory = $this->demoContentDirectory();

        (object) $authorsCSVExporter = Users::createExporter('csv');
        (object) $authorMetaCSVExporter = UserMeta::createExporter('csv');
        (object) $postsCSVExporter = Posts::createExporter('csv');
        (object) $postMetaCSVExporter = PostMeta::createExporter('csv');
        (object) $commentsCSVExporter = Comments::createExporter('csv');
        (object) $commentMetaCSVExporter = CommentMeta::createExporter('csv');
        (object) $termsCSVExporter = Terms::createExporter('csv');
        (object) $taxonomiesCSVExporter = Taxonomies::createExporter('csv');
        (object) $termRelationshipsCSVExporter = TermRelationships::createExporter('csv');
        (object) $optionsCSVExporter = Options::createExporter('csv');


        (object) $authorsSQLExporter = Users::createExporter('sql');
        (object) $authorMetaSQLExporter = UserMeta::createExporter('sql');
        (object) $postsSQLExporter = Posts::createExporter('sql');
        (object) $postMetaSQLExporter = PostMeta::createExporter('sql');
        (object) $commentsSQLExporter = Comments::createExporter('sql');
        (object) $commentMetaSQLExporter = CommentMeta::createExporter('sql');
        (object) $termsSQLExporter = Terms::createExporter('sql');
        (object) $taxonomiesSQLExporter = Taxonomies::createExporter('sql');
        (object) $termRelationshipsSQLExporter = TermRelationships::createExporter('sql');
        (object) $optionsSQLExporter = Options::createExporter('sql');




        $authorsCSVExporter->exportToFile("{$demoContentDirectory}/Users.csv");
        $authorMetaCSVExporter->exportToFile("{$demoContentDirectory}/Usermeta.csv");
        $postsCSVExporter->exportToFile("{$demoContentDirectory}/Posts.csv");
        $postMetaCSVExporter->exportToFile("{$demoContentDirectory}/Postmeta.csv");
        $commentsCSVExporter->exportToFile("{$demoContentDirectory}/Comments.csv");
        $commentMetaCSVExporter->exportToFile("{$demoContentDirectory}/Commentmeta.csv");
        $termsCSVExporter->exportToFile("{$demoContentDirectory}/Terms.csv");
        $taxonomiesCSVExporter->exportToFile("{$demoContentDirectory}/Term-taxonomy.csv");
        $termRelationshipsCSVExporter->exportToFile("{$demoContentDirectory}/Term-relationships.csv");
        $optionsCSVExporter->exportToFile("{$demoContentDirectory}/Options.csv");



        $authorsSQLExporter->exportToFile("{$demoContentDirectory}/Users.sql");
        $authorMetaSQLExporter->exportToFile("{$demoContentDirectory}/Usermeta.sql");
        $postsSQLExporter->exportToFile("{$demoContentDirectory}/Posts.sql");
        $postMetaSQLExporter->exportToFile("{$demoContentDirectory}/Postmeta.sql");
        $commentsSQLExporter->exportToFile("{$demoContentDirectory}/Comments.sql");
        $commentMetaSQLExporter->exportToFile("{$demoContentDirectory}/Commentmeta.sql");
        $termsSQLExporter->exportToFile("{$demoContentDirectory}/Terms.sql");
        $taxonomiesSQLExporter->exportToFile("{$demoContentDirectory}/Term-taxonomy.sql");
        $termRelationshipsSQLExporter->exportToFile("{$demoContentDirectory}/Term-relationships.sql");
        $optionsSQLExporter->exportToFile("{$demoContentDirectory}/Options.sql");

    }

    private function copyStyleToMasterTheme()
    {
        if (is_dir($this->masterThemePath())) {
            if ($this->stylesDirectory() !== '/') {
                exec("sudo rm -rf ".$this->stylesDirectory('master'));
                exec("sudo cp -a {$this->stylesDirectory()}/. ".$this->stylesDirectory('master'));
            }
            
        }
    }

    private function masterThemePath()
    {
        return '/var/app/demos/corebox/wp-content/themes/Corebox';
    }

    private function stylesDirectory($type = null)
    {
        (string) $stylesDirectory = ($type === 'master')?  "{$this->masterThemePath()}/Storage/SQL/Styles/{$this->styleName}" : "/var/app/downloads/corebox/styles/{$this->styleName}";   



        if (!is_dir($stylesDirectory)) {
            mkdir($stylesDirectory);
        }

        return $stylesDirectory;
    }

    private function demoContentDirectory()
    {
        (string) $demoContentDirectory = "/var/app/downloads/corebox/demos/{$this->styleName}";

        if (!is_dir($demoContentDirectory)) {
            mkdir($demoContentDirectory);
        }

        return $demoContentDirectory;
    }

}

