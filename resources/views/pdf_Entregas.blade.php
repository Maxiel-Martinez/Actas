<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta de entrega de activos tecnológicos</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 12px;
        }
        h1, h2, h3 {
            text-align: center;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size:12px;
        }
        th {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            color: aliceblue;
            background-color: #8A2991;
        }
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        .signature {
            margin-top: 40px;
            display: flex;
            flex-direction: row;
        }
        p {
            font-size: 11px;
        }
        .firmas_p {
            font-size: 15px;
        }
    </style>
</head>
<body>
<script src="{{asset('js/styles.js')}}"></script>
<img src="data:image/png;base64,{{$rutaLogo}}" alt="Logo" width="30%">

<!-- <th>{{$nombreRecibe}}</th> -->

    <h1>F25 TEC 0401 Acta de entrega de activos tecnológicos</h1>
    
    <p>
        <strong>Fecha de entrega:</strong> {{$fechaEntrega}}
        <strong>Por concepto de:</strong> {{$concepto}}
        <strong>Caso No:</strong> {{$numeroCaso}}
        <strong>Tipo:</strong> {{$tipoEscritorio}}
    </p>

    <p>
        <strong>Datos del equipo anterior:</strong><br>
        Serial del equipo anterior: {{$serialEquipoAnterior}}<br>
        No. de activo: {{$activoEquipoAnterior}}
    </p>

    <p>
        Entre los suscritos a saber, <strong>Andres Fernando Camacho Contreras</strong>, mayor de edad y vecino de la ciudad de Bogotá D.C., e identificado con la cédula de ciudadanía No. 1013620994, quien obra en nombre y representación de <strong>AMÉRICAS BPS S.A.</strong>, en su calidad de <strong>Director Operaciones IT</strong>, quien en adelante se denominará <strong>EL EMPLEADOR</strong>, y por la otra, <strong>{{strtoupper($nombres)}}</strong> identificado(a) con la cédula de ciudadanía número <strong>{{$cedula}}</strong> expedida en <strong>{{$expedicionCedula}}</strong> en calidad de <strong>EL TRABAJADOR</strong>, manifiestan que han efectuado EL PRESENTE COMÚN ACUERDO, acorde a las especificaciones anexas:
    </p>

    <p>
        <strong>CLÁUSULA PRIMERA: EL EMPLEADOR</strong> en forma concomitante a la suscripción de la presente acta le hace entrega a <strong>EL TRABAJADOR</strong> el equipo cómputo y los demás elementos tecnológicos que a continuación se relacionan:
    </p>

    <table>
        <tr>
            <th>Servicio</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Serial</th>
            <th>Activo</th>
        </tr>
        <tr>
            <td>{{$dispositivo}}</td>
            <td>{{$marcaDispositivo ?? 'N/A'}}</td>
            <td>{{$modeloDispositivo ?? 'N/A'}}</td>
            <td>{{$serialDispositivo ?? 'N/A'}}</td>
            <td>{{$activoDispositivo ?? 'N/A'}}</td>
        </tr>
        <tr>
            <td>Monitor</td>
            <td>{{$marcaMonitor ?? 'N/A'}}</td>
            <td>{{$modeloMonitor ?? 'N/A'}}</td>
            <td>{{$serialMonitor ?? 'N/A'}}</td>
            <td>{{$activoMonitor ?? 'N/A'}}</td>
        </tr>
        <tr>
            <td>Mouse</td>
            <td>{{$raton}}</td>
            <td>{{$raton}}</td>
            <td>{{$raton}}</td>
            <td>{{$raton}}</td>
        </tr>
        <tr>
            <td>Teclado</td>
            <td>{{$teclado}}</td>
            <td>{{$teclado}}</td>
            <td>{{$teclado}}</td>
            <td>{{$teclado}}</td>
        </tr>
        <tr>
            <td>Diadema</td>
            <td>{{$marcaDiadema ?? 'N/A' }}</td>
            <td>{{$marcaDiadema ?? 'N/A' }}</td>
            <td>{{$serialDiadema ?? 'N/A' }}</td>
            <td>{{$serialDiadema ?? 'N/A' }}</td>
        </tr>
    </table>
    <table>
        <tr>
            <th colspan="4">Celular</th>
        </tr>
        <tr>
            <td>IMEI 1</td>
            <td>{{$IMEI ?? 'N/A'}}</td>
            <td>IMEI 2</td>
            <td>{{$IMEI2 ?? 'N/A'}}</td>
        </tr>
    </table>
    <table>
        <tr>
            <th>Observaciones</th>
        </tr>
        <tr>
        <td>{{$observaciones ?? 'N/A'}}</td>
        </tr>
    </table>
    <p>
        <strong>PARÁGRAFO PRIMERO: EL TRABAJADOR</strong> declara recibir en buen estado y a entera satisfacción el Equipo de Cómputo, los componentes y accesorios que se entregan de forma concomitante con la suscripción de la presenta acta, por lo anterior, el Equipo de Cómputo, los componentes y accesorios, que se mencionan en esta acta se encuentra bajo la custodia y cuidado de <strong>EL TRABAJADOR</strong>; Igualmente, la perdida, daño o destrucción de cualquiera de los elementos entregados en esta acta constituye una falta grave meritoria de la terminación del contrato de trabajo, <strong>EL TRABAJADOR</strong> autoriza con la firma del presente documento, que en caso de pérdida o extravío, daño o destrucción de cualquiera de los elementos tecnológicos por cualquier motivo, le sea deducido o descontado el valor depreciado del bien entregado en custodia para el ejercicio de sus labores, en todo caso el empleador dará a conocer el valor del descuento al trabajador valor que se tendrá en cuenta conforme a los procedimiento establecidos por la compañía, generando la deducción de la sumas que se le adeuden por salarios, prestaciones sociales, vacaciones, intereses de cesantía, pagos de naturaleza extralegal, eventuales indemnizaciones y cualquier otra acreencia a que pueda tener derecho en vigencia del contrato de trabajo o al momento de terminación del contrato de trabajo por cualquier motivo.
    </p>

    <p>
        <strong>PARÁGRAFO SEGUNDO:</strong> En concordancia con lo anterior, <strong>EL TRABAJADOR</strong> se obliga de manera especial a reportar inmediatamente a su jefe inmediato y al área de tecnología de la compañía cualquier novedad o variación en el Equipo de Cómputo, sus componentes o accesorios entregados, bien sea por hurto, extravío o avería.
    </p>

    <p>
        <strong>EL TRABAJADOR</strong> en el evento o ocurrencia mencionado en el presente parágrafo, se obliga a notificar mediante en la herramienta de gestión designada por la compañía o la línea de soporte Técnicos 4251700 Ext 43300.
    </p>

    <p>
        <strong>CLAUSULA SEGUNDA: EL TRABAJADOR</strong> reconoce y acepta que el Equipo de Cómputo, sus componentes y accesorios son de propiedad de <strong>EL EMPLEADOR</strong> y por lo tanto deberá devolverlo, en cualquier momento que <strong>EL EMPLEADOR</strong> lo requiera, así como también a la terminación del contrato de trabajo cualquiera fuere su causa, en las mismas condiciones que poseía al momento de la entrega, sin perjuicio del desgaste normal por uso.
    </p>

    <p>
        <strong>PARÁGRAFO:</strong> En caso de que <strong>EL TRABAJADOR</strong> incumpla su obligación de devolver el equipo de computo o sus accesorios relacionados en el presente documento en la terminación de su contrato de trabajo o cuando lo requiere el empleador, mediante el presente escrito autoriza de manera expresa al <strong>EMPLEADOR</strong>, para que el valor total de los mismos, se descuente, deduzca y/o compense del valor de la liquidación final de acreencias laborales al cual tenga derecho <strong>EL TRABAJADOR</strong> incluyendo el valor de salarios, prestaciones sociales, vacaciones, intereses de cesantías, pagos de naturaleza extralegal, eventuales indemnizaciones y cualquier otra acreencias a que pueda tener derecho al momento de terminación del contrato de trabajo o por cualquier causa.
    </p>

    <p>
        <strong>CLÁUSULA TERCERA:</strong> Teniendo en cuenta que el Equipo de Cómputo, componentes y sus accesorios son otorgados como herramienta de trabajo, <strong>EL TRABAJADOR</strong> se compromete a utilizarlos única y exclusivamente en las labores que desempeñe como <strong>TRABAJADOR</strong> al servicio de <strong>EL EMPLEADOR</strong>
    </p>

    <p>
        <strong>CLÁUSULA CUARTA:</strong> El Equipo de Cómputo, componentes y accesorios entregados al <strong>TRABAJADOR</strong> a la firma del presente documento se concede como un acto de mera libertad de <strong>EL EMPLEADOR</strong> y como una herramienta para que desarrolle a cabalidad sus funciones el TRABAJADOR; por lo cual, las partes de manera expresa reconocen y aceptan que el suministro mencionado en la presente cláusula no constituye salario, ni beneficio legal o extralegal alguno, así mismo reiteran que no constituyen retribución alguna, ni salario o en dinero o especie, ni tendrán incidencia salarial, prestaciones parafiscal o indemnizatoria, de conformidad por lo establecido por los artículos 15 y 16 de la ley 50 de 1990 y el artículo 17 de la ley 344 de 1996. Por tratarse de herramientas de trabajo que facilita <strong>EL EMPLEADOR</strong> por mera liberalidad, <strong>EL EMPLEADOR</strong>, se reserva la facultad de modificar, adicionar o suprimir este suministro sin que constituya ningún tipo de desmejora para <strong>EL TRABAJADOR</strong>, situación que <strong>EL TRABAJADOR</strong> desde ya acepta.
    </p>

    <p>
        <strong>CLÁUSULA QUINTA: EL TRABAJADOR</strong> manifiesta que ha sido advertido y acepta expresamente que no debe instalar en los Equipo de cómputo suministrados por <strong>EL EMPLEADOR</strong> ninguna clase de software licenciado o no ( incluyendo pero no limitando: a Free software, Open source, Licencia GPL (General PublicLicense) o copyright, Software de dominio público, Freeware, Shareware, Adware (Advertising spyware)) de igual forma, se compromete a no alterar la configuración del Equipo de cómputo que se asignó para el desempeño de su labor cumpliendo a cabalidad las políticas de seguridad establecidas por <strong>EL EMPLEADOR</strong> para garantizar la confidencialidad, integridad y seguridad de la información.
    </p>
    <div class="signature">
    <table>
        <tr>
            <td>
                <div class="container">
                    <p class="firmas_p">Firma de quien entrega: <img src="data:image/png;base64,{{$ruta1}}" alt="" width="100px" height="40px"></p>
                    <p class="firmas_p">Nombre: <b>{{$nombreRecibe}}</b></p>
                </div>
            </td>
            <td>
                <div class="container">
                    
                    <p class="firmas_p">Firma de quien recibe: <img src="data:image/png;base64,{{$ruta2}}" alt="" width="100px" height="40px"></p>
                    <p class="firmas_p">Nombre: <b>{{$nombres}}</b></p>

                    <p>
                        <strong>Fecha de entrega:</strong> {{$fechaEntrega}}<br>
                        <strong>Número de caso:</strong> {{$numeroCaso}}<br>
                        <strong>Campaña:</strong> {{$campana}}
                    </p>
                </div>
            </td>
        </tr>
    </table>
</div>
</body>
</html>