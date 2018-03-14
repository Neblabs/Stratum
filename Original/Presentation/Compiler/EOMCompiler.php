<?php

namespace Stratum\Original\Presentation\Compiler;

use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Presentation\EOM\Text;
use Stratum\Original\Presentation\EOM\Node;
use Stratum\Original\Presentation\Creator\EOMElementCreator;
use Stratum\Original\Presentation\Compiler\VariableResolver;
use Stratum\Original\Presentation\Creator\EOMNodeCreator;
use DOMNode;

Class EOMCompiler
{
    protected $html;
    protected $DOMNodes = [];
    protected $DOMDocument;
    protected $isMasterView = false;
    protected $isMasterViewHead = false;

    public static function createCompilerFrom(Node $node, Compiler $parentCompiler = null)
    {

        (string) $Element = Element::className();
        (string) $Text = Text::className();

        if ($node instanceof $Element) {
            return static::ElementOrComponentCompilerFrom($node, $parentCompiler);
        } elseif ($node instanceof $Text) {
            return new EOMTextCompiler($node);
        }

    }

    protected function getCompilerFromDOM(DOMNode $DOMNode)
    {
        (object) $EOMNodeCreator = EOMNodeCreator::getCreatorFrom($DOMNode);

        return Static::createCompilerFrom($EOMNodeCreator->create());
    }

    public static function ElementOrComponentCompilerFrom(Element $element, Compiler $parentCompiler = null)
    {
        (boolean) $hasNameAttribute = $element->name !== null;

        if ($element->is('stratumcomponent') and $hasNameAttribute) {
            
            return new ComponentCompiler($element, $parentCompiler);
        } 

        return new EOMElementCompiler($element, $parentCompiler);
    }
    
    public function __construct($htmlString, $isMasterView = false)
    {
        $this->html = $this->preCompileHTML(utf8_decode($htmlString));
        $this->DOMDocument = new \DOMDocument;
        $this->isMasterView = $isMasterView;
        $this->createDOMNodesFromHTMLString();
        
    }

    public function compiledPHPEOM()
    {
        return $this->phpEOMTemplate();
    }

    public function compiledPHPEOMMasterHead()
    {
        $this->DOMNodes = $this->masterHeadNodes;
        $this->isMasterViewHead = true;
        
        return $this->compiledPHPEOM();
    }

    protected function preCompileHTML($htmlString)
    {
        (object) $componentResolver = new ComponentResolver($htmlString);
        return $componentResolver->preCompiledHTML();
    }
    protected function createDOMNodesFromHTMLString()
    {

        libxml_use_internal_errors(true);
        $this->DOMDocument->loadHTML($this->contentWrappedByTemplateElementIfIsPartialView());
        libxml_clear_errors();

        $this->DOMNodes = $this->DOMNodes();

    }

    protected function contentWrappedByTemplateElementIfIsPartialView()
    {
        if ($this->isMasterView) {
            return $this->html;
        }

        return "<div>$this->html</div>";
    }

    protected function DOMNodes()
    {
        if ($this->isMasterView) {
            $this->masterHeadNodes = $this->DOMDocument->getElementsByTagName('head');
            return $this->DOMDocument->getElementsByTagName('body');
        }

        return $this->DOMDocument->getElementsByTagName('body')[0]->childNodes[0]->childNodes;
    }

    protected function phpEOMTemplate()
    {
        return <<<EOM
<?php

use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Presentation\EOM\Text;
use Stratum\Original\Presentation\Component;
use Stratum\Original\Presentation\EOM\GroupOfNodes;
use Stratum\Original\Presentation\FormattersHandler;
use Stratum\Original\Presentation\Compiler\VariableResolver;
use Stratum\Original\Presentation\ElementManagersQueue;
use Stratum\Original\Presentation\Element\Adder\ElementContentAdder;
use Stratum\Original\Presentation\Writer\EOMNodeWriter;
use Stratum\Original\Presentation\Balance\Cache\Writer\ComponentCacheWriter;
use Stratum\Original\Presentation\Balance\Flusher;
use Stratum\Original\Presentation\Balance\Cache\Writer\ViewCacheWriter;

(object) \$groupOfNodes = new GroupOfNodes;
(object) \$elementManagersQueue = new ElementManagersQueue;

{$this->EOMObjectDefinitions()}

{$this->writeToCache()}
return \$groupOfNodes;

EOM;
    }

    protected function EOMObjectDefinitions()
    {
        (string) $elementDefintions = '';
        foreach ($this->DOMNodes as $node) {

            (boolean) $isNotTextNode = ($node instanceOf \DOMText) === false;
            
            (boolean) $textNodeisNotEmpty = !empty(trim($node->nodeValue));
            (boolean) $isNotDOMComment = ($node instanceof \DOMComment) === false;

            if (($isNotTextNode or $textNodeisNotEmpty) and $isNotDOMComment) {

                (object) $EOMCompiler = $this->getCompilerFromDOM($node);

                $elementDefintions.= $EOMCompiler->compiledType();
            }
            
        }

        return $elementDefintions;
    }

    protected function writeToCache()
    {
        if ($this->isMasterView && (!$this->isMasterViewHead)) {
            return <<<VIEWCACHE
if (\$canBeCached) {
    (object) \$ViewCacheWriter = new ViewCacheWriter;
    
    \$ViewCacheWriter->write();
}

VIEWCACHE;
        }
    }










}