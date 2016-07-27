<?php namespace Argentum\LibreDTE\Document;

use Argentum\Common\Document\Invoice as CommonInvoice;

class Invoice extends CommonInvoice
{
    /**
     * Get DTE type
     *
     * @return string
     */
    public function getTypeDte()
    {
        return 33;
    }
}
