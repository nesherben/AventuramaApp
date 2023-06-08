<?php
class FacturaPDF extends tFPDF
{
    function Header()
    {
        // Encabezado de la página
        // Logo de la empresa

        $this->Image('logopie.png', 10, 10, 50);

        // Información de la empresa
        $this->SetFont('DejaVu', '', 12);
        $this->SetXY(10, 40);
        $this->Cell(0, 10, 'AVENTURAMA S.L.', 0, 1);
        $this->SetFont('DejaVu', 'B', 10);
        $this->SetXY(10, 45);
        $this->Cell(0, 10, 'www.aventurama.es', 0, 0, 'L', false, 'http://www.aventurama.es');
        $this->SetFont('DejaVu', '', 10);
        $this->SetXY(10, 50);
        $this->Cell(0, 10, "C/ Tiberíades 8, 5º A", 0, 1);
        $this->SetXY(10, 55);
        $this->Cell(0, 10, 'Madrid 28043', 0, 1);
        $this->SetXY(10, 60);
        $this->Cell(0, 10, 'Teléfono: 91 382 45 40', 0, 1);
        $this->SetXY(10, 65);
        $this->Cell(0, 10, 'Móvil: 669 521 332 / 686 131 266', 0, 1);
        $this->SetXY(10, 70);
        $this->Cell(0, 10, 'CIF: B-83363655', 0, 1);

        $this->SetFont('DejaVu', 'B', 20);
        $this->SetXY(-50, 20);
        $this->SetTextColor(200, 222, 0);
        $this->Cell(0, 10, 'FACTURA', 0, 1);
    }

    function Footer()
    {
        // Pie de página de la página
        // Información adicional y enlaces
        $this->SetXY(10, -15);
        $this->SetFont('DejaVu', 'I', 10);
        $this->Cell(0, 10, 'Visite nuestra WEB:', 0, 0, 'L');
        $this->SetTextColor(255, 0, 0);
        $this->SetXY(50, -16);
        $this->SetFont('DejaVu', 'U', 20);
        $this->Cell(0, 10, 'www.aventurama.es', 0, 0, 'L', false, 'http://www.aventurama.es');
        $this->SetTextColor(0, 0, 0);
        $this->SetXY(-40, -15);
        $this->SetFont('DejaVu', 'B', 16);
        $this->Cell(0, 10, 'Factura ya Firmada', 0, 0, 'R');
        $this->SetXY(90, -25);
        $this->SetFont('DejaVu', 'U', 10);
        $this->Cell(0, 10, 'Facebook', 0, 0, 'R', false, 'https://www.facebook.com/aventurama');
        $this->SetXY(120, -35);
        $this->SetFont('DejaVu', 'U', 10);
        $this->Cell(0, 10, 'Twitter', 0, 0, 'R', false, 'https://twitter.com/aventurama');
    }

    function Content($datos)
    {
        $precio = (float)$datos['PRECIO'];
        //1.1 ES EL PRECIO SIN IVA 10%
        $siniva = (float)($precio / (1.1));
        $iva = $precio - $siniva;


        $this->SetFont('DejaVu', 'B', 11);
        $this->SetXY(-90, 55);
        $this->Cell(0, 10, $datos['UNOMBRE'] . " " . $datos['UAPELLIDOS'], 0);
        $this->SetXY(-90, 60);
        $this->Cell(0, 10, $datos['UDIRECCION'] . " " . $datos["UDIRECCION2"], 0);
        $this->SetXY(-90, 65);
        $this->Cell(0, 10, "CP - " . $datos['CP'] . " " . " " . " " . $datos['LOCALIDAD'] . ", " . $datos['PROVINCIA'], 0);
        $this->SetFont('DejaVu', '', 11);

        $this->SetXY(-90, 120);
        $this->Cell(0, 10, "NIF/NIE: " . $datos['DNI'], 0);
        $this->SetXY(-90, 128);
        $this->Cell(0, 10, "Teléfono: " . $datos['TELEFONO'], 0);
        $this->SetXY(-90, 136);
        $this->Cell(0, 10, "Email: " . $datos['EMAIL'], 0);

        $this->SetXY(10, -115);
        // Contenido de la factura dinámico
        $this->SetFont('DejaVu', 'B', 11);
        $this->Cell(130, 10, 'Descripción del servicio', 1);
        $this->Cell(60, 10, 'Precio (€)', 1);
        $this->Ln(); // Salto de línea

        // Precio normal
        $this->SetFont('DejaVu', '', 11);
        $this->Cell(130, 10, $datos['CATEGORIA'] . ": " . $datos['ACTIVIDAD'] . " - " . $datos['TURNO'], 1);
        $this->Cell(60, 10, number_format($siniva, 2) . "€", 1);
        $this->Ln(); // Salto de línea

        // Información del IVA
        $this->SetFont('DejaVu', '', 11);
        $this->Cell(130, 10, 'IVA (10%)', 1);
        $this->Cell(60, 10, number_format($iva,2) . "€", 1);
        $this->Ln(); // Salto de línea

        // Total
        $this->SetFont('DejaVu', 'B', 14);
        $this->Cell(130, 10, 'Total a pagar', 1);
        $this->SetFont('DejaVu', 'BU', 14);
        $this->Cell(60, 10, number_format($precio,2) . "€", 1);
        $this->Ln(); // Salto de línea

        $this->SetXY(15, 230);
        $this->SetFont('DejaVu', '', 12);
        $this->Cell(0, 10, "FECHA DE VENCIMIENTO: CONTADO", 0);
        $this->SetXY(15, 245);
        $this->SetFont('DejaVu', '', 10);
        $this->Cell(0, 10, "FORMA DE PAGO: WEB", 0);
        $this->SetXY(15, 251);
        $this->Cell(0, 10, "TRANSFERENCIA BANCARIA A CC SANTANDER IBAN ES07 0049 1804 10 2110412864", 0);
        $this->SetXY(15, 257);
        $this->Cell(0, 10, "Por Favor, indique como referencia nuestro número de Factura, gracias.", 0);
        $this->SetXY(15, 263);

        $this->SetFont('DejaVu', '', 7);
        $this->Cell(0, 10, "*Factura generada automaticamente*", 0);
        /////////////////////
        // datos de la factura y usuario
        ////////////////////
        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        $this->SetXY(10, 90);
        $this->SetFont('DejaVu', '', 12);
        $this->Cell(0, 10, 'Factura nº:    ' . " " . " " . " " . $datos['FECHA'] . "" . $datos['ID'], 0);
        $this->SetXY(10, 96);
        $this->Cell(0, 10, 'Fecha:    ' . " " . " " . " " . (int)explode("-", $datos['FECHA'])[2] . " de " . $meses[(int)explode("-", $datos['FECHA'])[1] - 1] . " del " . explode("-", $datos['FECHA'])[0], 0);

        $this->SetXY(10, 112);
        $this->SetFont('DejaVu', 'B', 12);
        $this->Cell(0, 10, 'SUJETO PASIVO:', 0);
        $this->SetXY(10, 120);
        $this->SetFont('DejaVu', '', 12);
        $this->Cell(0, 10, $datos['UNOMBRE'] . " " . $datos['UAPELLIDOS'], 0);
        $this->SetXY(10, 128);
        $this->Cell(0, 10, $datos['UDIRECCION'] . " " . $datos["UDIRECCION2"], 0);
        $this->SetXY(10, 136);
        $this->Cell(0, 10, "CP - " . $datos['CP'] . " " . " " . " " . $datos['LOCALIDAD'] . ", " . $datos['PROVINCIA'], 0);

        $this->SetXY(10, 160);
        $this->SetFont('DejaVu', '', 10);
        $this->Cell(0, 10, "Información y observaciones:", 0);
        $this->SetXY(10, 166);
        $this->SetFont('DejaVu', '', 8);
        $this->Cell(0, 10, "Factura correspondiente al niño/a: " . " " . $datos["NNOMBRE"] . " " . $datos["NAPELLIDOS"], 0);
    }
}
