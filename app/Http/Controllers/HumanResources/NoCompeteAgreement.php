<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use PhpOffice\PhpWord\Style\Language;

class NoCompeteAgreement extends Controller
{
    public function noCompeteAgreement($postulant, $postulant_details, $company_id)
    {
        $social_reason = "";
        $company_name = "";
        $company_address = "";
        $employer = "";
        $senior = "EL SEÑOR";
        $name = strtoupper($postulant->name);
        $lastname = strtoupper($postulant->lastname); 
        $rfc = strtoupper($postulant_details->rfc);
        $position = strtoupper($postulant_details->position);
        $date_admission = date('d/m/Y', strtotime($postulant_details->date_admission));
        $address =strtoupper($postulant_details->address);

        //Promolife
        if($company_id == 1){
            $social_reason = "PROMO LIFE, S. DE R.L. DE C.V.";
            $employer = "C. RAÚL TORRES MÁRQUEZ";
            $company_name = "PROMO LIFE";

            $uno_a = 'ES UNA SOCIEDAD CONSTITUIDA DE CONFORMIDAD CON LAS LEYES DE LOS ESTADOS UNIDOS MEXICANOS, SEGÚN CONSTA EN LA ESCRITURA NÚMERO 108810, DE FECHA 23 DE SEPTIEMBRE DE 2011, OTORGADA ANTE EL LICENCIADO F. JAVIER ARCE GARGOLLO, TITULAR DE LA NOTARÍA NÚMERO 74 DE LA CIUDAD DE MÉXICO.';    
            $uno_b = 'SU REPRESENTANTE CUENTA CON LAS FACULTADES SUFICIENTES PARA OBLIGAR A SU REPRESENTADA EN LOS TÉRMINOS DE ESTE CONVENIO, SEGÚN CONSTA EN LA ESCRITURA NÚMERO 108810, DE FECHA 23 DE SEPTIEMBRE DE 2011, OTORGADA ANTE EL LICENCIADO F. JAVIER ARCE GARGOLLO, TITULAR DE LA NOTARÍA NÚMERO 74 DE LA CIUDAD DE MÉXICO, MISMAS QUE A LA FECHA NO LE HAN SIDO REVOCADAS, LIMITADAS O MODIFICADAS DE FORMA ALGUNA.';    
            $uno_c = 'ES SU DESEO CELEBRAR EL PRESENTE CONVENIO, SUJETO A LOS TÉRMINOS Y CONDICIONES QUE MÁS ADELANTE SE INDICAN.';    
            
            $company_address = 'SAN ANDRES ATOTO 155 PISO 1 LOC. B, UNIDAD SAN ESTEBAN. NAUCALPAN DE JUÁREZ ESTADO DE MÉXICO, C.P. 53550.';
        }
        
        //BH tardemarket
        if($company_id == 2){
            $social_reason = "BH TRADE MARKET, S.A. DE C.V.";
            $employer = "C. DAVID LEVY HANO";
            $company_name = "BH TRADE MARKET";

            $uno_a = 'ES UNA SOCIEDAD CONSTITUIDA DE CONFORMIDAD CON LAS LEYES DE LOS ESTADOS UNIDOS MEXICANOS, SEGÚN CONSTA EN LA ESCRITURA NÚMERO 1881, DE FECHA 25 DE NOVIEMBRE DE 2014, OTORGADA ANTE EL LICENCIADO CLAUDIA GABRIELA FRANCÓZ GÁRATE, TITULAR DE LA NOTARÍA NÚMERO 153 DEL ESTADO DE MÉXICO.';   
            $uno_b = 'SU REPRESENTANTE CUENTA CON LAS FACULTADES SUFICIENTES PARA OBLIGAR A SU REPRESENTADA EN LOS TÉRMINOS DE ESTE CONVENIO, SEGÚN CONSTA EN LA ESCRITURA NÚMERO 1881, DE FECHA 28 DE NOVIEMBRE DE 2014, OTORGADA ANTE EL LICENCIADO CLAUDIA GABRIELA FRANCÓZ GÁRATE, TITULAR DE LA NOTARÍA NÚMERO 153 DEL ESTADO DE MÉXICO, MISMAS QUE A LA FECHA NO LE HAN SIDO REVOCADAS, LIMITADAS O MODIFICADAS DE FORMA ALGUNA. ';    
            $uno_c = 'ES SU DESEO CELEBRAR EL PRESENTE CONVENIO, SUJETO A LOS TÉRMINOS Y CONDICIONES QUE MÁS ADELANTE SE INDICAN.';    
            
            $company_address = 'SAN ANDRES ATOTO 155 PISO 1 LOC. B, UNIDAD SAN ESTEBAN. NAUCALPAN DE JUÁREZ ESTADO DE MÉXICO, C.P. 53550.'; 
        }
        
        //Trademarket 57
        if($company_id== 4){
            $social_reason = "TRADE MARKET 57, S.A. DE C.V."; 
            $employer = "C. MÓNICA REYES RESENDIZ";
            $company_name = "TRADE MARKET 57";
            $senior = "LA SEÑORITA";

            $uno_a = 'ES UNA SOCIEDAD CONSTITUIDA DE CONFORMIDAD CON LAS LEYES DE LOS ESTADOS UNIDOS MEXICANOS, SEGÚN CONSTA EN LA ESCRITURA NÚMERO 2062, DE FECHA 08 DE JULIO DE 2015, OTORGADA ANTE EL LICENCIADO CLAUDIA GABRIELA FRANCÓZ GÁRATE, TITULAR DE LA NOTARÍA NÚMERO 153 DEL ESTADO DE MÉXICO.';    
            $uno_b = 'SU REPRESENTANTE CUENTA CON LAS FACULTADES SUFICIENTES PARA OBLIGAR A SU REPRESENTADA EN LOS TÉRMINOS DE ESTE CONVENIO, SEGÚN CONSTA EN LA ESCRITURA NÚMERO 2062, DE FECHA 08 DE JULIO DE 2015, OTORGADA ANTE EL LICENCIADO CLAUDIA GABRIELA FRANCÓZ GÁRATE, TITULAR DE LA NOTARÍA NÚMERO 153 DEL ESTADO DE MÉXICO, MISMAS QUE A LA FECHA NO LE HAN SIDO REVOCADAS, LIMITADAS O MODIFICADAS DE FORMA ALGUNA. ';    
            $uno_c = 'ES SU DESEO CELEBRAR EL PRESENTE CONVENIO, SUJETO A LOS TÉRMINOS Y CONDICIONES QUE MÁS ADELANTE SE INDICAN.';    

            $company_address = 'SAN ANDRES ATOTO 155 PLANTA BAJA, UNIDAD SAN ESTEBAN. NAUCALPAN DDE JUÁREZ. ESTADO DE MÉXICO 53550';
        } 
        
        
       
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->getSettings()->setMirrorMargins(true);
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::ES_ES));

        //Global styles
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(9);
        
        //Font Styles
        $phpWord->setDefaultParagraphStyle(
            array(
                'align' => 'both',
                'lineHeight' => 1.15
            )
        );

        $textLineBoldCenter = array(
            'underline' => 'single',
            'bold' => true,
            'align'=> 'center'
        );
       

        $bodyBoldStyle = array(
            'align' => 'both',
            'lineHeight' => 1.15,
            'bold' => true
        ); 

        $bodyNormalStyle = array(
            'align' => 'both',
            'lineHeight' => 1.15,
            'bold' => false
        ); 

        $center = array(
            'align'=> 'center'
        );

        //Secctions
        $section = $phpWord->addSection();
        $htmlsection= new \PhpOffice\PhpWord\Shared\Html();
        

        //Setting page margins
        $phpWord->getSettings()->setMirrorMargins(false);
        $sectionStyle = $section->getStyle();
        $sectionStyle->setMarginLeft(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(3));
        $sectionStyle->setMarginRight(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(3));
        $sectionStyle->setMarginTop(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5));
        $sectionStyle->setMarginBottom(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5));

        $section2 = "<p><b>CONVENIO DE NO COMPETENCIA Y CONFIDENCIALIDAD (EL “<u>CONVENIO</u>”) QUE CELEBRAN POR UNA PARTE $social_reason, REPRESENTADA EN ESTE ACTO POR $senior $employer, A QUIEN EN LO SUCESIVO SE LE DENOMINARÁ COMO “ $company_name ”, Y POR OTRA PARTE EL/LA C. $name $lastname POR SU PROPIO DERECHO, A QUIEN EN LO SUCESIVO SE LE DENOMINARÁ COMO “EMPLEADO” Y CONJUNTAMENTE CON $company_name COMO LAS “PARTES”, AL TENOR DE LAS SIGUIENTES DECLARACIONES Y CLÁUSULAS.</b></p>";
        $htmlsection->addHtml($section, $section2);

        $section->addText(
            'DECLARACIONES',
            $textLineBoldCenter,$center
        );

        $multilevelListStyleName = 'multilevel';

        $singleListStyleName = 'multilevel';

        $phpWord->addNumberingStyle(
            $multilevelListStyleName,
            [
                'type' => 'multilevel',
                'levels' => [
                    ['format' => 'upperRoman', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360],
                    ['format' => 'lowerLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720],
                    ['format' => 'decimal', 'text' => '%3.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720],
                    ['format' => 'lowerRoman', 'text' => '(%4)', 'left' => 720, 'hanging' => 360, 'tabPos' => 720],

                ],
            ]
        );

        $listItemRun = $section->addListItemRun(0, $multilevelListStyleName,[]);
        $listItemRun->addText('DECLARA '. $company_name . ', A TRAVÉS DE SU REPRESENTANTE LEGAL Y BAJO PROTESTA DE DECIR VERDAD QUE:',$bodyBoldStyle);
        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText($uno_a,$bodyNormalStyle);
        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText($uno_b,$bodyNormalStyle);
        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText($uno_c,$bodyNormalStyle);

        $listItemRun = $section->addListItemRun(0, $multilevelListStyleName,[]);
        $listItemRun->addText('DECLARA EL EMPLEADO, POR SU PROPIO DERECHO Y BAJO PROTESTA DE DECIR VERDAD QUE:  ',$bodyBoldStyle);
        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText('ES UNA PERSONA FÍSICA CON PLENA CAPACIDAD JURÍDICA Y PLENO USO DE SUS FACULTADES LEGALES PARA SUSCRIBIR EL PRESENTE CONVENIO.',$bodyNormalStyle);
        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText('CUENTA CON REGISTRO FEDERAL DE CONTRIBUYENTES '. $rfc .' SEGÚN CONSTA EN LA CEDULA DE IDENTIFICACIÓN FISCAL, EXPEDIDA POR LA SECRETARIA DE HACIENDA Y CRÉDITO PÚBLICO.',$bodyNormalStyle);
        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText('DERIVADO DE SU RELACIÓN COMERCIAL Y/O PROFESIONAL Y/O PERSONAL CON '. $company_name . ', CON SUS ACCIONISTAS Y/O CON SUS DIRECTORES, QUE HA TENIDO DESDE HACE VARIOS AÑOS.  HA TENIDO Y SEGUIRÁ TENIENDO ACCESO A INFORMACIÓN CONFIDENCIAL DE '.$company_name.' Y/O DE SUS CLIENTES, ASÍ COMO A INFORMACIÓN CONFIDENCIAL DE GIRO COMERCIAL Y “KNOW HOW” DEL NEGOCIO DE '. $company_name.', RECONOCIENDO QUE DICHA INFORMACIÓN CONFIDENCIAL REPRESENTA UN ACTIVO Y VENTAJA COMPETITIVA DE '. $company_name. ' EN EL MERCADO, Y FRENTE A SUS COMPETIDORES.',$bodyNormalStyle);
        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText('RECONOCE QUE LA REVELACIÓN O USO INDEBIDO DE LA INFORMACIÓN CONFIDENCIAL DE '. $company_name . ', ASÍ COMO A INFORMACIÓN CONFIDENCIAL DE GIRO COMERCIAL Y “KNOW HOW” DEL NEGOCIO DE ' . $company_name . ', CAUSARÍA A ESTA UN DAÑO SERIO E IRREPARABLE, ASÍ COMO AL DESARROLLO DE SUS OPERACIONES Y ACTIVIDADES COMERCIALES.',$bodyNormalStyle);
        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText('CUENTA CON LA CAPACIDAD Y RECURSOS SUFICIENTES PARA CELEBRAR Y EJECUTAR LAS OBLIGACIONES QUE ASUME CONFORME AL PRESENTE CONVENIO.',$bodyNormalStyle);
        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText('QUE LAS OBLIGACIONES CONTRAÍDAS POR EL EMPLEADO EN VIRTUD DE ESTE CONVENIO SON VÁLIDAS, LEGALMENTE VINCULANTES Y EXIGIBLES EN SU CONTRA, DE ACUERDO CON LOS TÉRMINOS INDICADOS EN EL MISMO.',$bodyNormalStyle);


        $section->addText(
            'EN VIRTUD DE LO ANTERIOR Y NO EXISTIENDO DOLO, VIOLENCIA, LESIÓN, O ALGÚN OTRO TIPO DE VICIO EN EL CONSENTIMIENTO, LAS PARTES SE SOMETEN A LAS SIGUIENTES:',
            $bodyNormalStyle,
        );

        $section->addText(
            'CLÁUSULAS',
            $textLineBoldCenter,$center
        );       

        $section2 = "<p><b>PRIMERA.- OBJETO.</b> EL EMPLEADO SE OBLIGA CON $company_name A LA ABSTENCIÓN DE LA REALIZACIÓN DE CUALQUIER ACTO QUE REPRESENTE DE CUALQUIER MANERA DIRECTA O INDIRECTA COMPETENCIA COMERCIAL O DE CUALQUIER OTRA ÍNDOLE A $company_name, SUS ACCIONISTAS, FILIALES, SUBSIDIARIAS, SUS EMPLEADOS, REPRESENTANTES, AGENTES, CONSULTORES Y/O APODERADOS DE CONFORMIDAD CON LO ESTABLECIDO EN EL PRESENTE CONVENIO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>ASIMISMO, <b>EL EMPLEADO</b> SE OBLIGA A GUARDAR ESTRICTA CONFIDENCIALIDAD SOBRE TODA LA INFORMACIÓN CONFIDENCIAL (SEGÚN DICHO TÉRMINO SE DEFINE MÁS ADELANTE) A LA QUE TUVO ACCESO CON ANTERIORIDAD A ESTE ACTO, A LA QUE PUDIERA TENER ACCESO Y/O QUE LE SEA SUMINISTRADA O PROPORCIONADA, YA SEA DE FORMA VERBAL, ESCRITA O POR CUALQUIER OTRO MEDIO, POR $company_name, SUS EMPLEADOS, FUNCIONARIOS, ACCIONISTAS, REPRESENTANTES, AGENTES, CONSULTORES Y/O APODERADOS O CUALQUIER OTRA PERSONA RELACIONADA CON $company_name.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>SEGUNDA.- NO COMPETENCIA (NON COMPETE). EL EMPLEADO</b> SE OBLIGA A NO PARTICIPAR, DIRECTA O INDIRECTAMENTE O A TRAVÉS DE TERCEROS, COMO EMPRESARIO, EMPLEADO, FUNCIONARIO, GERENTE, GERENTE DE VENTAS, AYUDANTE GENERAL, SOCIO, DIRECTOR, AGENTE, PROPIETARIO, ACCIONISTA O EN CUALQUIER OTRA CAPACIDAD, EN ALGUNA ACTIVIDAD QUE TENGA IDENTIDAD O SE RELACIONE CON EL GIRO COMERCIAL DE $company_name O DE CUALQUIERA DE SUS SUBSIDIARIAS Y/O CUALQUIER PERSONA FÍSICA O MORAL QUE APAREZCA EN LA LISTA DE CLIENTES (SEGÚN DICHO TÉRMINO SE DEFINE MÁS ADELANTE), INCLUYENDO CUALQUIER SOCIEDAD CUYO OBJETO SOCIAL SEA LA VENTA, COMPRA, IMPRESIÓN, FABRICACIÓN, COMERCIALIZACIÓN, IMPORTACIÓN, EXPORTACIÓN Y/O DISTRIBUCIÓN DE PRODUCTOS Y/O ARTÍCULOS PROMOCIONALES Y DE PUBLICIDAD, PRODUCTOS MÉDICOS, DE SALUD, TECNOLÓGICOS Y/O CUALQUIER OTRO PRODUCTO QUE PUEDA SER SUMINISTRADO POR $company_name ASÍ COMO EL DESARROLLO DE PROYECTOS DE COMERCIALIZACIÓN Y PUBLICIDAD DE PRODUCTOS EN MÉXICO Y/O EN EL EXTRANJERO.</p>";
        $htmlsection->addHtml($section, $section2);
        
        $section2 = "<p>A SU VEZ, <b>EL EMPLEADO</b> SE OBLIGA A ABSTENERSE DE INCURRIR EN CUALQUIER NEGOCIACIÓN, PLÁTICAS, ACERCAMIENTOS, OFERTAS, ASÍ COMO INDUCIR, SOLICITAR, CONTRATAR, INTERFERIR EN LA RELACIÓN COMERCIAL, DE FORMA DIRECTA O INDIRECTA CON CUALQUIERA DE LOS CLIENTES, PROVEEDORES, PROSPECTOS, EMPLEADOS, CONTRATISTAS Y/O VENDEDORES O CUALQUIER PERSONA FÍSICA O MORAL QUE APAREZCA EN LA LISTA DE CLIENTES DE $company_name O CUALQUIERA DE SUS SUBSIDIARIAS RESPECTO DE LOS SERVICIOS Y/O PRODUCTOS QUE $company_name, PRESTA, OFRECE Y/O VENDE AL PÚBLICO GENERAL (CONJUNTAMENTE CON LO ESTABLECIDO EN EL PÁRRAFO INMEDIATO ANTERIOR, LA “<b>NO COMPETENCIA</b>”).</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>LA OBLIGACIÓN DE NO COMPETENCIA ESTABLECIDA EN LA PRESENTE CLÁUSULA SUBSISTIRÁ DURANTE UN PLAZO DE 5 AÑOS (60 MESES) POSTERIORES A LA TERMINACIÓN DE LA VIGENCIA (SEGÚN DICHO TÉRMINO SE DEFINE MÁS ADELANTE) DEL PRESENTE CONVENIO. </p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>TERCERA.-  CONFIDENCIALIDAD.</b>  EL TÉRMINO <i>INFORMACIÓN CONFIDENCIAL</i>, SEGÚN SE UTILICE EN EL PRESENTE CONVENIO, Y EN TÉRMINOS DE LOS ARTÍCULOS 82, 83 Y 85 DE LA LEY DE LA PROPIEDAD INDUSTRIAL, SIGNIFICA E INCLUYE TODA INFORMACIÓN PROPIEDAD DE $company_name, QUE SE DETALLA MÁS ADELANTE, Y QUE SEA REVELADA A <b>EL EMPLEADO</b> CON MOTIVO DE SU RELACIÓN COMERCIAL Y/O PROFESIONAL Y/O PERSONAL Y/O CONTRACTUAL. ESTA INFORMACIÓN ESTARÁ REGULADA POR LAS DISPOSICIONES APLICABLES DE LA LEY DE LA PROPIEDAD INDUSTRIAL, Y EN EL CÓDIGO PENAL.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>EL EMPLEADO</b> SE OBLIGA EXPRESAMENTE EN ESTE ACTO A NO DIVULGAR, DIRECTA O INDIRECTAMENTE, NI BAJO NINGUNA CIRCUNSTANCIA Y GUARDAR ABSOLUTA CONFIDENCIALIDAD EN TODO MOMENTO, RESPECTO A TODA LA TODA AQUELLA INFORMACIÓN TRANSMITIDA, YA SEA POR VÍA ORAL, POR CUALQUIER TIPO DE DOCUMENTOS, POR MEDIOS ELECTRÓNICOS O CUALQUIER OTRO MEDIO, QUE GUARDE UNA RELACIÓN DIRECTA O INDIRECTA CON LA INFORMACIÓN A LA CUAL TENGA O PUEDA TENER ACCESO <b>EL EMPLEADO</b> DE $company_name Y/O DE CUALQUIER SUBSIDIARIA Y/O DE SUS CLIENTES, SIENDO CONSIDERADOS ENTRE ESTA INFORMACIÓN, DE MANERA ENUNCIATIVA MÁS NO LIMITATIVA, LA QUE PUEDA INCLUIR INFORMACIÓN DE MERCADOTECNIA, DATOS PERSONALES, SISTEMAS, ASUNTOS JURÍDICOS, RECURSOS HUMANOS, PLANES DE NEGOCIOS, BASES DE DATOS, PROCEDIMIENTOS INTERNOS, LOGÍSTICA, IMPORTACIONES Y/O EXPORTACIONES, INFORMACIÓN FISCAL, INFORMACIÓN FINANCIERA, INFORMACIÓN RELACIONADA CON ACCIONISTAS Y SOCIOS, PRECIOS, PROVEEDORES, CLIENTES, SECRETOS INDUSTRIALES, ASIGNACIÓN DE CUENTAS, DATOS PERSONALES (SEGÚN DICHO TÉRMINO SE DEFINE MÁS ADELANTE), LA LISTA DE CLIENTES, PLANES DE NEGOCIOS, SOCIOS ESTRATÉGICOS, MEDIOS Y FORMAS DE DISTRIBUCIÓN DE PRODUCTOS Y/O DE PRESTACIÓN DE SERVICIOS, “KNOW HOW”, CUALQUIER INFORMACIÓN GENERADA DURANTE SU GESTIÓN EN $company_name EN VIRTUD DE CUALQUIER RELACIÓN COMERCIAL, CONTRACTUAL Y/O DE CUALQUIER OTRA ÍNDOLE Y/O TODA AQUELLA INFORMACIÓN QUE DE MANERA DIRECTA O INDIRECTA SE RELACIONE CON LOS PUNTOS ENUNCIADOS ANTERIORMENTE (LA “<b>INFORMACIÓN CONFIDENCIAL</b>”), DEBIENDO <b>EL EMPLEADO</b> EN TODO MOMENTO MANTENERLA FUERA DEL ALCANCE DEL PÚBLICO EN GENERAL, ASÍ COMO TRATARLA COMO PRIVADA Y NO AUTORIZAR SU PUBLICACIÓN O DIVULGACIÓN DE NINGUNA MANERA.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>EL EMPLEADO</b> CONVIENE EN CONCEDER A PARTIR DE ESTA FECHA, TRATO CONFIDENCIAL Y DE ACCESO RESTRINGIDO A LA INFORMACIÓN CONFIDENCIAL A LA QUE PUDIERA TENER ACCESO POR CUALQUIER MOTIVO, COMPROMETIÉNDOSE A MANTENER EN SU PODER ÚNICAMENTE LA INFORMACIÓN CONFIDENCIAL ESTRICTAMENTE NECESARIA PARA EL CUMPLIMIENTO DE SUS OBLIGACIONES COMERCIALES, CONTRACTUALES O DE CUALQUIER OTRA ÍNDOLE FRENTE A $company_name, ASÍ COMO A CONSERVARLA EN SU PODER EL TIEMPO QUE SEA ESTRICTAMENTE NECESARIO (LA “<b>CONFIDENCIALIDAD</b>”).</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>LAS PARTES ACUERDAN QUE LA OBLIGACIÓN DE CONFIDENCIALIDAD ESTABLECIDA EN LA PRESENTE CLÁUSULA SERÁ APLICABLE A TODA LA INFORMACIÓN CONFIDENCIAL QUE $company_name LE HAYA TRANSMITIDO EL EMPLEADO POR CUALQUIER MEDIO PREVIO A LA CELEBRACIÓN DEL PRESENTE CONVENIO Y A CUALQUIER INFORMACIÓN CONFIDENCIAL A LA QUE HAYA TENIDO ACCESO O TENGA ACCESO COMO CONSECUENCIA DE CUALQUIER ACTO O HECHO DERIVADO DE SU RELACIÓN COMERCIAL Y/O PROFESIONAL Y/O PERSONAL Y/O CONTRACTUAL CON $company_name DERIVADO DE RELACIÓN COMERCIAL Y/O PROFESIONAL Y/O PERSONAL Y/O CONTRACTUAL CON $company_name O SUS SUBSIDIARIA</p>";
        $htmlsection->addHtml($section, $section2);
     
        $section2 = "<p>A SU VEZ, LAS PARTES ACUERDAN QUE TODOS LOS TÉRMINOS, CONDICIONES Y ACUERDOS CONTENIDOS EN EL PRESENTE CONVENIO SERÁN CONSIDERADOS COMO CONFIDENCIALES Y ESTARÁN SUJETOS A TODAS LAS OBLIGACIONES DE CONFIDENCIALIDAD CONTENIDAS EN EL PRESENTE CONVENIO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>PARA LOS EFECTOS DE ESTE CONVENIO, NO SE CONSIDERARÁ INFORMACIÓN CONFIDENCIAL AQUELLA INFORMACIÓN:</p>";
        $htmlsection->addHtml($section, $section2); 

        $listItemRun = $section->addListItemRun(2, $singleListStyleName,[]);
        $listItemRun->addText('QUE SEA O LLEGUE A SER DEL DOMINIO PÚBLICO, SIN MEDIAR INCUMPLIMIENTO DE ESTE CONVENIO POR EL EMPLEADO.',$bodyNormalStyle);

        $listItemRun = $section->addListItemRun(2, $singleListStyleName,[]);
        $listItemRun->addText('QUE '. $company_name . ' AUTORICE EL EMPLEADO, PREVIA NOTIFICACIÓN Y POR ESCRITO, PARA SU DIVULGACIÓN O ENTREGA DE INFORMACIÓN CONFIDENCIAL A UN TERCERO; Y ',$bodyNormalStyle);
        
        $listItemRun = $section->addListItemRun(2, $singleListStyleName,[]);
        $listItemRun->addText('QUE EL EMPLEADO SEA INSTRUIDO MEDIANTE REQUERIMIENTO JUDICIAL DE ENTREGAR PARCIAL O TOTALMENTE LA INFORMACIÓN CONFIDENCIAL, EN CUYO CASO EL EMPLEADO, PREVIO A CUALQUIER ENTREGA DE LA INFORMACIÓN CONFIDENCIAL, SE OBLIGA A INFORMAR INMEDIATAMENTE A '.$company_name.' RESPECTO DE DICHO REQUERIMIENTO Y DEBERÁ REALIZAR LOS ACTOS QUE ESTÉN A SU ALCANCE PARA PREVENIR LA ENTREGA DE LA INFORMACIÓN CONFIDENCIAL PREVIO A INFORMAR A '. $company_name .'.',$bodyNormalStyle);

        $section2 = "<p><b>CUARTA.- SECRETO INDUSTRIAL.</b> PARA TODOS LOS EFECTOS A QUE HAYA LUGAR, LAS PARTES CONSIDERARÁN QUE LA INFORMACIÓN CONFIDENCIAL SE EQUIPARA O ES EQUIVALENTE AL SECRETO INDUSTRIAL CONFORME A LA LEY DE LA PROPIEDAD INDUSTRIAL.</p>";
        $htmlsection->addHtml($section, $section2); 

        $section2 = "<p>LAS PARTES CONVIENEN QUE EN CASO DE QUE <b>EL EMPLEADO</b> INCUMPLA CUALQUIERA DE LAS OBLIGACIONES A SU CARGO QUE SE DERIVAN DEL PRESENTE CONVENIO O EN CASO DE QUE NO LAS CUMPLA DE LA MANERA CONVENIDA, $company_name PODRÁ EJERCER LAS ACCIONES PENALES, CIVILES Y ADMINISTRATIVAS QUE CORRESPONDAN, INCLUYENDO, ENUNCIATIVA MAS NO LIMITATIVAMENTE, LAS DISPUESTAS POR LOS ARTÍCULOS 223 Y 224 DE LA LEY DE PROPIEDAD INDUSTRIAL Y POR LOS ARTÍCULOS 211 Y 211 BIS 7 DEL CÓDIGO PENAL FEDERAL; LO ANTERIOR SIN DETRIMENTO DE EXIGIR EL PAGO DE LOS DAÑOS Y PERJUICIOS QUE CONFORME A DERECHO CORRESPONDAN.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>QUINTA.- DATOS PERSONALES. EL EMPLEADO</b> SE OBLIGA RESPECTO DE LOS DATOS PERSONALES A LOS CUALES TENGA ACCESO, INCLUYENDO SIN LIMITAR, LOS NOMBRES, YA SEA DE PERSONA FÍSICA O MORAL, TELÉFONOS, DIRECCIONES, CORREOS ELECTRÓNICOS, INFORMACIÓN DE COMPRA O VENTA, EMPLEADOS, PUESTOS DE LOS EMPLEADOS, ASÍ COMO CUALQUIER OTRA INFORMACIÓN A LA QUE PUDIERA TENER ACCESO RESPECTO DE LOS CLIENTES, PROVEEDORES, PROSPECTOS, EMPLEADOS, CONTRATISTAS Y/O VENDEDORES PASADOS, PRESENTES Y FUTUROS DE $company_name, CUALQUIERA DE SUS SUBSIDIARIAS O CUALQUIER PERSONA FÍSICA O MORAL QUE APAREZCA EN LA LISTA DE CLIENTES (LOS “<b>DATOS PERSONALES</b>”), A QUE ÉSTOS SERÁN TRATADOS DE CONFORMIDAD CON LO DISPUESTO EN LA LEY FEDERAL DE PROTECCIÓN DE DATOS PERSONALES EN POSESIÓN DE LOS PARTICULARES PUBLICADA EN EL DIARIO OFICIAL DE LA FEDERACIÓN (EN ADELANTE “DOF”) EL CINCO DE JULIO DE DOS MIL DIEZ, ASÍ COMO SU REGLAMENTO PUBLICADO EN EL DOF EL VEINTIUNO DE DICIEMBRE DE DOS MIL ONCE, SIN PERJUICIO DE LAS DEMÁS DISPOSICIONES LEGALES APLICABLES.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>LA OBLIGACIÓN SEÑALADA EN EL PÁRRAFO INMEDIATO ANTERIOR, SIN LIMITARSE A ESTA, LE SERÁ APLICABLE LA LISTA DE CLIENTES QUE SE ADJUNTA AL PRESENTE COMO ANEXO 1, ASÍ COMO A SUS ACCIONISTAS, FILIALES, SUBSIDIARIAS, EMPLEADOS, REPRESENTANTES, AGENTES, CONSULTORES Y/O APODERADOS (LA “<b>LISTA DE CLIENTES</b>”), MISMA QUE SE ACTUALIZARÁ DE TIEMPO EN TIEMPO A DISCRECIÓN DE $company_name. LO ANTERIOR, EN EL ENTENDIDO DE QUE LAS SOCIEDADES SEÑALADAS EN LA LISTA DE CLIENTES SE CONSIDERARÁN EN TODO MOMENTO COMO CLIENTES DE $company_name, INDEPENDIENTEMENTE DE LA ASIGNACIÓN DE CUENTAS QUE LLEVE A CABO $company_name. </p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>EN TODO MOMENTO <b>EL EMPLEADO</b> SE OBLIGA A RESPETAR Y HACER CUMPLIR LOS DERECHOS DE LOS TITULARES DE LOS DATOS PERSONALES EN TÉRMINOS DE LA LEGISLACIÓN CITADA, SIN PERJUICIO DE LAS DEMÁS OBLIGACIONES Y RESPONSABILIDADES QUE LES CORRESPONDAN CONFORME AL PRESENTE CONVENIO O CUALQUIER OTRA LEGISLACIÓN APLICABLE. </p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>SEXTA. - USO DE LA INFORMACION CONFIDENCIAL. EL EMPLEADO</b> SE OBLIGA A QUE EN NINGÚN MOMENTO Y POR NINGÚN MOTIVO, PODRÁ DUPLICAR, COPIAR, EDITAR O REPRODUCIR POR NINGÚN MÉTODO, USAR PARA SU PROPIO BENEFICIO O DE TERCERO LA INFORMACIÓN CONFIDENCIAL A LA QUE TUVIERA ACCESO COMO CONSECUENCIA DEL CUMPLIMIENTO DE SUS OBLIGACIONES COMERCIALES, CONTRACTUALES O DE CUALQUIER OTRA ÍNDOLE FRENTE A $company_name. ASIMISMO, SE OBLIGA A NO USAR PARA SU PROPIO BENEFICIO O DE TERCERO LAS HERRAMIENTAS O SISTEMAS DE TRABAJO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>LO ESTABLECIDO EN EL PÁRRAFO INMEDIATO ANTERIOR LE SERÁ APLICABLE EN TODO MOMENTO A LAS OBLIGACIONES DE NO COMPETENCIA ESTABLECIDAS EN LA CLÁUSULA SEGUNDA DEL PRESENTE CONVENIO, EN EL ENTENDIDO QUE EL USO DE LA INFORMACIÓN CONFIDENCIAL EN CONTRAVENCIÓN A LA PRESENTE CLÁUSULA RESULTARÁ EN INCUMPLIMIENTO POR PARTE DE <b>EL EMPLEADO</b>, TANTO DE LA OBLIGACIÓN DE CONFIDENCIALIDAD COMO LA OBLIGACIÓN DE NO COMPETENCIA, DE CONFORMIDAD CON EL PRESENTE CONVENIO.  </p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>SÉPTIMA. - OBLIGACIONES DE EL EMPLEADO</b> EN TODO MOMENTO DURANTE LA VIGENCIA DEL PRESENTE CONVENIO, <b>EL EMPLEADO</b> ESTARÁ OBLIGADA A: </p>";
        $htmlsection->addHtml($section, $section2);

        $listItemRun = $section->addListItemRun(3, $multilevelListStyleName,[]);
        $listItemRun->addText('CUMPLIR CON LAS OBLIGACIONES DE NO COMPETENCIA ESTABLECIDAS EN LA CLÁUSULA SEGUNDA DEL PRESENTE CONVENIO.',$bodyNormalStyle);

        $listItemRun = $section->addListItemRun(3, $multilevelListStyleName,[]);
        $listItemRun->addText('TOMAR LAS MEDIDAS NECESARIAS PARA PREVENIR EL ROBO O LA PÉRDIDA DE LA INFORMACIÓN CONFIDENCIAL, ASÍ COMO PROTEGERLA CON LA MISMA DILIGENCIA QUE PROTEGE SU PROPIA INFORMACIÓN CONFIDENCIAL;',$bodyNormalStyle);

        $listItemRun = $section->addListItemRun(3, $multilevelListStyleName,[]);
        $listItemRun->addText('RESPONDER POR CUALQUIER VIOLACIÓN A LA OBLIGACIÓN DE NO COMPETENCIA Y/O CONFIDENCIALIDAD AQUÍ ESTABLECIDA, ASÍ COMO A LOS DERECHOS DE PROPIEDAD INTELECTUAL DE' . $company_name .' EN ESTE ACTO, EL EMPLEADO SE OBLIGA A RESPONDER DIRECTAMENTE ANTE '.$company_name. ' POR CUALQUIER VIOLACIÓN A LA NO COMPETENCIA Y/O CONFIDENCIALIDAD QUE SEA COMETIDA POR EL EMPLEADO O EN GENERAL POR CUALQUIER OTRA PERSONA VINCULADA A EL EMPLEADO; Y ;',$bodyNormalStyle);

        $listItemRun = $section->addListItemRun(3, $multilevelListStyleName,[]);
        $listItemRun->addText('DEVOLVER INMEDIATAMENTE A '. $company_name .' TODA LA INFORMACIÓN CONFIDENCIAL, INCLUYENDO LOS DATOS PERSONALES, EN CASO DE QUE ASÍ LO SOLICITE '. $company_name. ' Y/O CUANDO EL PRESENTE CONVENIO SEA TERMINADO O RESCINDIDO POR CUALQUIER MOTIVO. UNA VEZ TERMINADO EL PRESENTE CONVENIO, EL EMPLEADO NO PODRÁ, POR NINGÚN MOTIVO, MANTENER EN SU POSESIÓN INFORMACIÓN CONFIDENCIAL.',$bodyNormalStyle);

        $section2 = "<p><b>OCTAVA. - INCUMPLIMIENTO POR PARTE DE EL EMPLEADO</b>. EN CASO DE QUE <b>EL EMPLEADO</b>, O CUALESQUIER PERSONAS VINCULADAS DE CUALQUIER FORMA CON EL MISMO, INCUMPLA CON ALGUNA DE LAS OBLIGACIONES DERIVADAS DE ESTE CONVENIO, ASÍ COMO LAS OBLIGACIONES RESPECTO A LA NO COMPETENCIA, A LA INFORMACIÓN CONFIDENCIAL Y/O LAS OBLIGACIONES ESTABLECIDAS EN LA CLÁUSULA SÉPTIMA DEL PRESENTE CONVENIO, <b>EL EMPLEADO</b> ESTARÁ SUJETA A LA PENA CONVENCIONAL (SEGÚN DICHO TÉRMINO SE DEFINE MÁS ADELANTE) ESTABLECIDA EN LA CLÁUSULA NOVENA DEL PRESENTE CONVENIO; SIN PERJUICIO DE QUE DEBERÁ RESPONDER A LOS DAÑOS Y PERJUICIOS OCASIONADOS A $company_name, SUS EMPLEADOS, FUNCIONARIOS, ACCIONISTAS, O CUALQUIER OTRA PERSONA RELACIONADA CON $company_name. </p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>LO ANTERIOR SIN PERJUICIO DEL DERECHO DE $company_name DE EJERCITAR LAS DEMÁS ACCIONES LEGALES, SANCIONES Y MULTAS QUE PROCEDAN POR LA VIOLACIÓN A LOS DERECHOS DE PROPIEDAD INTELECTUAL DE $company_name, INCLUIDAS, DE MANERA ENUNCIATIVA MAS NO LIMITATIVA, EN LA LEY DE LA PROPIEDAD INDUSTRIAL Y EN EL CÓDIGO PENAL FEDERAL O CUALESQUIER LEYES QUE EN UN FUTURA LAS SUSTITUYAN Y/O SEA APLICABLE.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>EL EMPLEADO</b> EN ESTE ACTO SE OBLIGA A SACAR EN PAZ Y A SALVO A $company_name, SUS EMPLEADOS, FUNCIONARIOS, ACCIONISTAS, O CUALQUIER OTRA PERSONA RELACIONADA CON $company_name DE CUALQUIER RECLAMACIÓN, DEMANDA, DENUNCIA, PROCEDIMIENTO JUDICIAL O EXTRAJUDICIAL O INVESTIGACIÓN DE CUALQUIER NATURALEZA, QUE SEAN CONSECUENCIA DEL INCUMPLIMIENTO DE LAS OBLIGACIONES DE NO COMPETENCIA Y/O CONFIDENCIALIDAD PREVISTAS EN EL PRESENTE CONVENIO POR PARTE DE <b>EL EMPLEADO</b>, O CUALESQUIER PERSONAS VINCULADAS DE CUALQUIER FORMA CON EL MISMO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>LAS OBLIGACIONES A CARGO DE <b>EL EMPLEADO</b> PREVISTAS EN LA PRESENTE CLÁUSULA NO REQUERIRÁN DE LA EXISTENCIA DE UNA SENTENCIA JUDICIAL QUE SE PRONUNCIE SOBRE EL INCUMPLIMIENTO POR PARTE DE <b>EL EMPLEADO</b> A LAS OBLIGACIONES PREVISTAS EN EL PRESENTE CONVENIO. </p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>NOVENA. - PENA CONVENCIONAL.  EL EMPLEADO</b> ACEPTA Y SE OBLIGA EXPRESAMENTE A QUE EN CASO DE INCURRIR EN INCUMPLIMIENTO DE CUALQUIERA DE SUS OBLIGACIONES DERIVADAS DEL PRESENTE CONVENIO, INCLUYENDO DE MANERA ENUNCIATIVA MÁS NO LIMITATIVA, LAS OBLIGACIONES DE NO COMPETENCIA, DE CONFIDENCIALIDAD Y/O LAS OBLIGACIONES ESTABLECIDAS EN LA CLÁUSULA SÉPTIMA DEL PRESENTE CONVENIO, DEBERÁ PAGAR A $company_name POR CONCEPTO DE PENA CONVENCIONAL LA CANTIDAD DE $5,000,000.00 (CINCO MILLONES DE PESOS 00/100 MONEDA NACIONAL) (LA “PENA CONVENCIONAL”). LA PENA CONVENCIONAL DEBERÁ SER PAGADA DENTRO DE LOS 2 (DOS) DÍAS HÁBILES CONTADOS A PARTIR DE QUE SE ACTUALICE EL INCUMPLIMIENTO CONFORME AL PRESENTE CONVENIO, EN EL DOMICILIO DE $company_name QUE SE SEÑALA EN ESTE CONVENIO O EN LA CUENTA BANCARIA QUE SEÑALE PARA TALES EFECTOS $company_name.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>EN CASO DE INCUMPLIMIENTO, <b>EL EMPLEADO</b> FACULTA A $company_name A COMPENSAR CUALESQUIER MONTOS QUE EXISTAN O PUDIEREN EXISTIR EN UN FUTURO A FAVOR DE <b>EL EMPLEADO</b>, INCLUYENDO, COMISIONES, SUELDOS FUTUROS Y/O REPARTOS DE UTILIDADES, LAS CANTIDADES NECESARIAS PARA CUBRIR EL IMPORTE DE LA PENA CONVENCIONAL DE ACUERDO CON LO PREVISTO EN ESTA CLÁUSULA.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>DÉCIMA.-  VIGENCIA.</b> EL PRESENTE CONVENIO SURTIRÁ SUS EFECTOS A PARTIR DEL DÍA DE SU FIRMA Y HASTA 5 (CINCO) AÑOS, ES DECIR, (60 MESES) A PARTIR DE QUE SE TERMINE CUALQUIER RELACIÓN COMERCIAL Y/O CONTRACTUAL O DE CUALQUIER OTRA ÍNDOLE QUE EXISTA ACTUALMENTE O EN EL FUTURO ENTRE <b>EL EMPLEADO</b> Y $company_name Y/O CUALQUIERA DE SUS SUBSIDIARIAS, ACCIONISTAS Y/O PROPIETARIOS (EN LO SUCESIVO LA “<b><u>VIGENCIA</u></b>”).</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>DÉCIMA PRIMERA. - CESIÓN. EL EMPLEADO</b> SE OBLIGA A NO CEDER, TRASPASAR O DE CUALQUIER FORMA TRANSMITIR LOS DERECHOS Y/U OBLIGACIONES A SU FAVOR Y/O CARGO DERIVADOS DEL PRESENTE CONVENIO, EN EL ENTENDIDO DE QUE SI <b>EL EMPLEADO</b> INCUMPLE CON ESTA OBLIGACIÓN ESTARÁ SUJETO A LA PENA CONVENCIONAL ESTABLECIDA EN LA CLÁUSULA NOVENA DEL PRESENTE CONVENIO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>DÉCIMA SEGUNDA. - MODIFICACIONES</b>. LA NOVACIÓN DEL PRESENTE CONVENIO NUNCA SE PRESUMIRÁ, POR LO QUE CUALQUIER ADICIÓN O MODIFICACIÓN QUE LAS PARTES DESEEN REALIZAR AL CONTENIDO DEL PRESENTE CONVENIO, DEBERÁ EFECTUARSE MEDIANTE CONVENIO REALIZADO POR ESCRITO Y FIRMADO POR LAS PARTES, EN DONDE EXPRESAMENTE CONSTEN LOS CAMBIOS O ACUERDOS ADICIONALES.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>EN CASO DE QUE SE REALICE CUALQUIER MODIFICACIÓN AL PRESENTE CONVENIO, DE CONFORMIDAD CON LO ESTABLECIDO EN EL PÁRRAFO ANTERIOR, LA MISMA ÚNICAMENTE AFECTARÁ LA MATERIA SOBRE LA QUE EXPRESAMENTE VERSE, POR LO TANTO, SE MANTENDRÁN EN VIGOR LAS DEMÁS CLÁUSULAS DE ESTE ACUERDO DE VOLUNTADES EN TODOS SUS TÉRMINOS Y CONDICIONES.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>DÉCIMA TERCERA. - DOMICILIOS</b>. LAS PARTES SEÑALAN COMO SUS DOMICILIOS CONVENCIONALES PARA TODO LO RELATIVO AL PRESENTE CONVENIO, LOS SIGUIENTES</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>EMPLEADO: $address</b></p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>$company_name</b>: $company_address</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>TODOS LOS AVISOS Y/O NOTIFICACIONES QUE LAS PARTES DEBAN DARSE O EFECTUARSE CON MOTIVO DE LA CELEBRACIÓN Y CUMPLIMIENTO DE ESTE CONVENIO, SERÁN POR ESCRITO Y SE ENTREGARÁN PERSONALMENTE O POR CORREO CERTIFICADO, EN AMBOS CASOS CON ACUSE DE RECIBO, DIRIGIDO A LOS DOMICILIOS ANTES MENCIONADOS O A CUALQUIER OTRO DOMICILIO QUE CADA PARTE SEÑALE CON POSTERIORIDAD.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>CUALQUIERA DE LAS PARTES PODRÁ, DE TIEMPO EN TIEMPO, SEÑALAR COMO SU DOMICILIO PARA LOS FINES DE ESTE ACUERDO CUALQUIER OTRO DOMICILIO, PREVIO AVISO POR ESCRITO AL RESPECTO ENTREGADO CON 10 (DIEZ) DÍAS NATURALES DE ANTICIPACIÓN DEL CAMBIO A LA OTRA PARTE. MIENTRAS QUE NO SE RECIBA UNA NOTIFICACIÓN DE CAMBIO DE DOMICILIO DE CONFORMIDAD CON LO DISPUESTO EN LA PRESENTE CLÁUSULA, LAS NOTIFICACIONES EFECTUADAS A LOS DOMICILIOS ARRIBA INDICADOS SERÁN EFECTIVAS.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>DÉCIMA CUARTA. - ACUERDOS, CONTRATOS Y CONVENIOS PREVIOS</b>. LAS PARTES MANIFIESTAN EXPRESAMENTE QUE ESTÁN CONFORMES CON QUE LAS CLÁUSULAS DEL PRESENTE CONVENIO REFLEJAN FIEL Y PUNTUALMENTE LA TOTALIDAD DE LOS ACUERDOS TOMADOS POR ELLAS, Y QUE SUSTITUYEN CUALQUIER OTRO ACUERDO, CONTRATO O CONVENIO QUE LAS PARTES O SUS REPRESENTANTES O APODERADOS PUDIERAN HABER CELEBRADO PREVIAMENTE.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>DÉCIMA QUINTA. - TÍTULOS DE LAS CLÁUSULAS</b>. LOS TÍTULOS O DENOMINACIÓN DE LAS CLÁUSULAS DE ESTE CONVENIO TIENEN COMO ÚNICO OBJETO FACILITAR SU IDENTIFICACIÓN, POR LO QUE NO DETERMINARÁN EN FORMA ALGUNA SU INTERPRETACIÓN O CONTENIDO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>DÉCIMA SEXTA. - DIVISIBILIDAD</b>: LA INVALIDEZ DE CUALQUIERA DE LAS DISPOSICIONES CONTENIDAS EN EL PRESENTE CONVENIO NO AFECTARÁ LA VALIDEZ DE CUALQUIER OTRA DISPOSICIÓN Y LAS DISPOSICIONES PREVALECIENTES SE MANTENDRÁN EN PLENO VIGOR Y EFECTO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>DÉCIMA SÉPTIMA. - LEYES Y TRIBUNALES COMPETENTES</b>. LAS PARTES ACUERDAN SOMETER LA INTERPRETACIÓN Y CUMPLIMIENTO DEL PRESENTE CONVENIO A LAS LEYES Y TRIBUNALES DEL ESTADO DE MÉXICO, RENUNCIANDO, POR TANTO, A CUALQUIER FUERO DEL DOMICILIO O VECINDAD QUE TUVIEREN O LLEGAREN A ADQUIRIR EN EL FUTURO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>LEÍDO QUE FUE POR LAS PARTES EL PRESENTE CONVENIO, LO RATIFICARON Y FIRMARON PARA CONSTANCIA, EN EL ESTADO DE MÉXICO, EL DÍA <b>$date_admission</b></p>";
        $htmlsection->addHtml($section, $section2);

        $section->addText(''); 
        
        $cellRowSpan = array('width' => 5000);
        $table = $section->addTable([]);
        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('EMPLEADO',$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText($company_name,$bodyBoldStyle, $center);

        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('',$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText('',$bodyBoldStyle, $center);

        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('',$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText('',$bodyBoldStyle, $center);

        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('__________________________________',$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText('__________________________________',$bodyBoldStyle, $center);

        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText($name. ' '. $lastname. '<w:br/>'.$position,$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText($social_reason. '<w:br/>'.'REPRESENTADA POR:'. '<w:br/>'.$employer ,$bodyBoldStyle, $center);
       
        $section->addPageBreak();

        $section->addText(
            'ANEXO 1',
            $bodyBoldStyle,$center
        ); 
        
        $section->addText(
            'LISTA DE CLIENTES',
            $textLineBoldCenter,$center
        );

        $section2 = "<p>LA PRESENTE LISTA DE CLIENTES INCLUIRÁ A TODAS LAS SOCIEDADES SUBSIDIARIAS Y FILIALES QUE FORMEN O LLEGAREN A FORMAR PARTE DEL GRUPO CORPORATIVO DE LAS SOCIEDADES CONOCIDAS CON LOS NOMBRES COMERCIALES QUE SE SEÑALAN A CONTINUACIÓN:</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>ASIMISMO, LA INFORMACIÓN CONFIDENCIAL SEÑALADA EN EL PRESENTE CONVENIO. INCLUYE TODA INFORMACIÓN POR LA VÍA QUE SEA QUE EL <b>EMPLEADO</b> OBTENGA DE LOS CLIENTES AQUÍ SEÑALADOS Y LOS QUE SE SIGAN GENERANDO MIENTRAS ESTÉ VIGENTE EL PRESENTE INSTRUMENTO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>ESTE DOCUMENTO ES ANEXO QUE FORMA PARTE INTEGRAL DEL CONVENIO DE NO COMPETENCIA Y CONFIDENCIALIDAD QUE CELEBRAN POR UNA PARTE $social_reason, Y POR OTRA PARTE EL/LA C. $name $lastname DE FECHA DEL DÍA FECHA DE INGRESO $date_admission.</p>";
        $htmlsection->addHtml($section, $section2);

        $section->addText(''); 

        $cellRowSpan = array('width' => 5000);
        $table = $section->addTable([]);
        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('EMPLEADO',$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText($company_name,$bodyBoldStyle, $center);

        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('',$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText('',$bodyBoldStyle, $center);

        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('',$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText('',$bodyBoldStyle, $center);

        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('__________________________________',$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText('__________________________________',$bodyBoldStyle, $center);

        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText($name. ' '. $lastname. '<w:br/>'.$position,$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText($social_reason. '<w:br/>'.'REPRESENTADA POR:'. '<w:br/>'.$employer ,$bodyBoldStyle, $center);
       
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . 'NO COMPETE' . ' ' . strtoupper($name) .' '. strtoupper($lastname) . '.doc');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");
        
    }

}
