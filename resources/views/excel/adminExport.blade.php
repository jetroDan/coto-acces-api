<?php
$visitorType = ['Visitante', 'Visitante', 'Proveedor', 'Proveedor Corporativo'];
$entryType = ['Código QR', 'Manual'];
$preRegistered = ['NO', 'SI'];
$vehicleType = ['Camión', 'Automóvil', 'Pick-up', 'Motocicleta', 'Bicicleta', 'Taxi, transporte privado, etc...'];
?>
<table>
    <thead>
    <tr>
        <th>Puerta Ingreso</th>
        <th>Guardia Ingreso</th>
        <th>Puerta Salida</th>
        <th>Guardia de Salida</th>
        <th>Tipo de entrada</th>
        <th>Nombre del visitante</th>
        <th>Visitante o Proveedor</th>
        <th>Identificación (foto)</th>
        <th>Tipo de Vehículo</th>
        <th>Placas</th>
        <th>Color del Vehículo</th>
        <th>Fecha de Ingreso</th>
        <th>Hora de Ingreso</th>
        <th>Fecha de Salida</th>
        <th>Hora de Salida</th>
        <th>Tiempo de Estadía</th>
        <th>Casa visitada o Dirección</th>
        <th>Nombre de la Persona Visitada</th>
        <th>Motivo de la visita</th>
        <th>¿Pre-registrado?</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($data as $entry)
        <tr>
            <td>{{$entry->entry_door}}</td>
            <td>{{(isset($entry->entryguarddata) ? $entry->entryguarddata->name : null )}}</td>
            <td>{{$entry->exit_door}}</td>
            <td>{{(isset($entry->exitguarddata) ? $entry->exitguarddata->name : null ) }}</td>
            <td>{{$entryType[$entry->entry_type]}}</td>
            <td>{{$entry->visitor_name}}</td>
            <td>{{$visitorType[$entry->visitor_type]}}</td>
            <td>=HYPERLINK("{{$entry->INE_url}}", "Abrir foto")</td>
            <td>{{(isset($entry->vehicle_type) ? $vehicleType[$entry->vehicle_type] : null )}}</td>
            <td>{{$entry->car_plates}}</td>
            <td style="background-color:{{$entry->car_color}} ;"></td>
            <td>{{$entry->entry_date}}</td>
            <td>{{$entry->entry_time}}</td>
            <td>{{$entry->exit_date}}</td>
            <td>{{$entry->exit_time}}</td>
            <td>{{$entry->visit_time}}</td>
            <td>{{$entry->visited_address}}</td>
            <td>{{$entry->visited_name}}</td>
            <td>{{$entry->visit_motive}}</td>
            <td>{{$preRegistered[$entry->pre_registered]}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
