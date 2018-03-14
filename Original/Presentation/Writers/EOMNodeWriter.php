<?php

namespace Stratum\Original\Presentation\Writer;

use Stratum\Original\Presentation\Balance\Cache\Writer\ViewCacheWriter;
use Stratum\Original\Presentation\Compiler\ComponentCompiler;
use Stratum\Original\Presentation\Component;
use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Presentation\EOM\Node;
use Stratum\Original\Presentation\EOM\Text;

Abstract Class EOMNodeWriter
{
    protected $node;
    protected $isCachingView;

    abstract public function write();

    public static function createFrom($node)
    {
        (string) $Element = Element::className();
        (string) $Text = Text::className();
        (string) $Component = Component::className();

        if ($node instanceof $Element) {
            $writer = EOMElementWriter::createElementWriterFrom($node);
        } elseif ($node instanceof $Text) {
            $writer = new EOMTextWriter($node);
        } elseif ($node instanceof $Component) {
            $writer = new ComponentWriter($node);
        }

        return $writer;
    }

    public function __construct(Node $node)
    {
        $this->node = $node;
    }

    public function setIsCachingView($isCachingView)
    {
        $this->isCachingView = $isCachingView;
    }

    protected function isNodeFromComponent()
    {
        return $this->node->isPartOfNonCachableComponent();
    }

    protected function writtenComponent()
    {
        if (!ViewCacheWriter::componentHasAlradyBeenWritten($this->node->parentComponent())) {

            (object) $componentCompiler = ComponentCompiler::createFromComponent($this->node->parentComponent());

            return "<?php " . PHP_EOL .
                   $componentCompiler->compiledType() . 
                   "EOMNodeWriter::createFrom({$componentCompiler->componentVariable()})->write();" . PHP_EOL .
                    'Flusher::flush();' . PHP_EOL . 
                   "?> " . PHP_EOL;

            ViewCacheWriter::setWrittenComponent($this->node->parentComponent());            
        }
    }



}


