<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit94c7f0816fa203ee4c190ff0bde4f23b
{
    public static $prefixesPsr0 = array (
        'x' => 
        array (
            'xrstf\\Composer52' => 
            array (
                0 => __DIR__ . '/..' . '/xrstf/composer-php52/lib',
            ),
        ),
    );

    public static $classMap = array (

		'BEWPI_Abstract_Document' => __DIR__ . '/../..' . '/includes/abstracts/abstract-document.php',
		'BEWPI_Abstract_Invoice' => __DIR__ . '/../..' . '/includes/abstracts/abstract-invoice.php',
        'BEWPI_Abstract_Setting' => __DIR__ . '/../..' . '/includes/compatibility/abstract-bewpi-setting.php',
        'BEWPI_Abstract_Settings' => __DIR__ . '/../..' . '/includes/abstracts/abstract-settings.php',
        'BEWPI_Admin_Notices' => __DIR__ . '/../..' . '/includes/admin/class-admin-notices.php',
        'BEWPI_Debug_Log' => __DIR__ . '/../..' . '/includes/class-debug-log.php',
        'BEWPI_General_Settings' => __DIR__ . '/../..' . '/includes/admin/settings/class-general.php',
		'BEWPI_Invoice' => __DIR__ . '/../..' . '/includes/class-invoice.php',   
		'BEWPI_Packing_Slip' => __DIR__ . '/../..' . '/includes/class-packing-slip.php',
		'BEWPI_Template' => __DIR__ . '/../..' . '/includes/class-template.php',
		'BEWPI_Template_Settings' => __DIR__ . '/../..' . '/includes/admin/settings/class-template.php',
        'BEWPI_WC_Core_Compatibility' => __DIR__ . '/../..' . '/includes/compatibility/class-wc-core-compatibility.php',
        'BEWPI_WC_Data_Compatibility' => __DIR__ . '/../..' . '/includes/compatibility/abstract-wc-data-compatibility.php',
        'BEWPI_WC_DateTime' => __DIR__ . '/../..' . '/includes/compatibility/class-wc-datetime.php',
        'BEWPI_WC_Order_Compatibility' => __DIR__ . '/../..' . '/includes/compatibility/class-wc-order-compatibility.php',
        'BEWPI_WC_Product_Compatibility' => __DIR__ . '/../..' . '/includes/compatibility/class-wc-product-compatibility.php',
		'BE_WooCommerce_PDF_Invoices' => __DIR__ . '/../..' . '/includes/woocommerce-pdf-invoices.php',
		'CGIF' => __DIR__ . '/..' . '/mpdf/mpdf/classes/gif.php',
        'CGIFCOLORTABLE' => __DIR__ . '/..' . '/mpdf/mpdf/classes/gif.php',
        'CGIFFILEHEADER' => __DIR__ . '/..' . '/mpdf/mpdf/classes/gif.php',
        'CGIFIMAGE' => __DIR__ . '/..' . '/mpdf/mpdf/classes/gif.php',
        'CGIFIMAGEHEADER' => __DIR__ . '/..' . '/mpdf/mpdf/classes/gif.php',
        'CGIFLZW' => __DIR__ . '/..' . '/mpdf/mpdf/classes/gif.php',
        'FPDF_TPL' => __DIR__ . '/..' . '/setasign/fpdi/fpdf_tpl.php',
        'FPDI' => __DIR__ . '/..' . '/setasign/fpdi/fpdi.php',
        'FilterASCII85' => __DIR__ . '/..' . '/setasign/fpdi/filters/FilterASCII85.php',
        'FilterASCIIHexDecode' => __DIR__ . '/..' . '/setasign/fpdi/filters/FilterASCIIHexDecode.php',
        'FilterLZW' => __DIR__ . '/..' . '/setasign/fpdi/filters/FilterLZW.php',
        'INDIC' => __DIR__ . '/..' . '/mpdf/mpdf/classes/indic.php',
        'MYANMAR' => __DIR__ . '/..' . '/mpdf/mpdf/classes/myanmar.php',
        'OTLdump' => __DIR__ . '/..' . '/mpdf/mpdf/classes/otl_dump.php',
        'PDFBarcode' => __DIR__ . '/..' . '/mpdf/mpdf/classes/barcode.php',
        'SEA' => __DIR__ . '/..' . '/mpdf/mpdf/classes/sea.php',
        'SVG' => __DIR__ . '/..' . '/mpdf/mpdf/classes/svg.php',
        'TTFontFile' => __DIR__ . '/..' . '/mpdf/mpdf/classes/ttfontsuni.php',
        'TTFontFile_Analysis' => __DIR__ . '/..' . '/mpdf/mpdf/classes/ttfontsuni_analysis.php',
        'UCDN' => __DIR__ . '/..' . '/mpdf/mpdf/classes/ucdn.php',
        'bmp' => __DIR__ . '/..' . '/mpdf/mpdf/classes/bmp.php',
        'cssmgr' => __DIR__ . '/..' . '/mpdf/mpdf/classes/cssmgr.php',
        'directw' => __DIR__ . '/..' . '/mpdf/mpdf/classes/directw.php',
        'fpdi_pdf_parser' => __DIR__ . '/..' . '/setasign/fpdi/fpdi_pdf_parser.php',
        'grad' => __DIR__ . '/..' . '/mpdf/mpdf/classes/grad.php',
        'mPDF' => __DIR__ . '/..' . '/mpdf/mpdf/mpdf.php',
        'meter' => __DIR__ . '/..' . '/mpdf/mpdf/classes/meter.php',
        'mpdfform' => __DIR__ . '/..' . '/mpdf/mpdf/classes/mpdfform.php',
        'otl' => __DIR__ . '/..' . '/mpdf/mpdf/classes/otl.php',
        'pdf_context' => __DIR__ . '/..' . '/setasign/fpdi/pdf_context.php',
        'tocontents' => __DIR__ . '/..' . '/mpdf/mpdf/classes/tocontents.php',
        'wmf' => __DIR__ . '/..' . '/mpdf/mpdf/classes/wmf.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit94c7f0816fa203ee4c190ff0bde4f23b::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit94c7f0816fa203ee4c190ff0bde4f23b::$classMap;

        }, null, ClassLoader::class);
    }
}
