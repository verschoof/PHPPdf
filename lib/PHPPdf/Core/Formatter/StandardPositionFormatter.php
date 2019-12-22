<?php

/*
 * Copyright 2011 Piotr Śliwa <peter.pl7@gmail.com>
 *
 * License information is in LICENSE file
 */

namespace PHPPdf\Core\Formatter;

use PHPPdf\Core\Node as Nodes,
    PHPPdf\Core\Document,
    PHPPdf\Core\Boundary;

/**
 * Calculates real position of node
 *
 * @author Piotr Śliwa <peter.pl7@gmail.com>
 */
class StandardPositionFormatter extends BaseFormatter
{
    public function format(Nodes\Node $node, Document $document)
    {
        $boundary = $node->getBoundary();
        if(!$boundary->isClosed())
        {
            list($x, $y) = $boundary->getFirstPoint()->toArray();

            $attributesSnapshot = $node->getAttributesSnapshot();

            $width = (isset($attributesSnapshot['width']) ? (int) $attributesSnapshot['width'] : 0);

            $diffWidth = $node->getWidth() - $width;
            $nodeWidth = $node->getWidth();
            $x += $nodeWidth;
            $yEnd = $y - $node->getHeight();
            $boundary->setNext($x, $y)
                     ->setNext($x, $yEnd)
                     ->setNext($x - $nodeWidth, $yEnd)
                     ->close();

            if($node->hadAutoMargins())
            {
                $node->translate(-$diffWidth/2, 0);
            }
        }
    }
}
