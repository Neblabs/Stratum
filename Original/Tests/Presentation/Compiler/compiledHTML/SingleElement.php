<\\?php

use Stratum\\Original\\Presentation\\EOM\\Element;
use Stratum\\Original\\Presentation\\EOM\\GroupOfNodes;
use Stratum\\Original\\Presentation\\FormattersHandler;

\(object\) \$groupOfNodes = new GroupOfNodes\(\[\]\);


\(object\) \$element = new Element\(\[
                \'type\' => \'div\'\,
                \'isVoid\' => false
            \]\);

\$groupOfNodes->add\(\$element\);


return \$groupOfNodes;
