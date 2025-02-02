@extends('layouts.app')

@section('content')
    <h1 class="text-4xl font-bold mb-3">Autószerviz Napló</h1>

    <!-- Kereső -->
    <div class="mb-4">
        <input type="text" id="searchName" class="border border-natural-200 p-2" placeholder="Ügyfél neve">
        <input type="text" id="searchCard" class="border border-natural-200 p-2" placeholder="Okmányazonosító">
        <button id="searchClient" class="p-2 bg-blue-700 text-white cursor-pointer">Keresés</button>
        <p id="searchError" class="text-red-600 mt-2"></p>
    </div>

    <div id="searchResult" class="mb-3"></div>

    <hr>

    <!-- Ügyfelek listája -->
    <h2 class="text-2xl font-bold my-3">Ügyfelek</h2>
    <div class="grid grid-cols-3">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-start">ID</th>
                    <th class="text-start">Név</th>
                    <th class="text-start">Okmányazonosító</th>
                </tr>
            </thead>
            <tbody id="clientList" class="w-full"></tbody>
        </table>
    
        <div class="col-span-2">
            <div id="clientCars"></div>
            <div id="carServices"></div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Ügyfelek lekérése
            function loadClients() {
                $.get('/api/clients', function(data) {
                    let rows = "";
                    data.forEach(client => {
                        rows += `<tr>
                        <td>${client.id}</td>
                        <td><a href="#" class="client-link underline text-blue-600" data-id="${client.id}">${client.name}</a></td>
                        <td>${client.idcard}</td>
                    </tr>`;
                    });
                    $("#clientList").html(rows);
                });
            }

            // Ügyfél autóinak betöltése AJAX-szal
            $(document).on("click", ".client-link", function() {
                let clientId = $(this).data("id");
                $.get(`/api/clients/${clientId}/cars`, function(cars) {
                    let html =
                        `<h3 class="text-2xl font-bold mb-3">Ügyfél autói</h3><table class="w-full"><tr>
                    <th>Car ID</th><th>Típus</th><th>Regisztráció</th><th>Saját márkás</th><th>Balesetek</th><th>Utolsó esemény</th><th>Időpont</th></tr>`;

                    cars.forEach(car => {
                        html += `<tr>
                        <td>
                            <a href="#" class="car-link underline text-blue-600" data-id="${car.client.id}, ${car.car_id}">
                                ${car.car_id}
                            </a>
                        </td>
                        <td>${car.type}</td>
                        <td>${car.registered || 'N/A'}</td>
                        <td>${car.ownbrand ? 'Igen' : 'Nem'}</td>
                        <td>${car.accident}</td>
                        <td>${car.service_logs.length ? car.service_logs.sort((a, b) => b.lognumber - a.lognumber)[0].event : 'N/A'}</td>
                        <td>${car.service_logs.length ? car.service_logs.sort((a, b) => b.lognumber - a.lognumber)[0].eventtime : 'N/A'}</td>
                    </tr>`;
                    });

                    html += "</table>";
                    $("#clientCars").html(html);
                    $("#carServices").addClass('hidden');
                });
            });

            // Autó adatainak betöltése
            $(document).on("click", ".car-link", function() {

                let dataId = $(this).data("id");

                let ids = dataId.split(',');

                let clientId = ids[0];
                let carId = ids[1];

                $.get(`/api/cars/${clientId}/${carId}/services`, function(services) {
                    let html =
                        `
                        <h3 class="text-2xl font-bold mb-3 mt-6">Szerviz</h3>
                        <table class="w-full">
                        <tr>
                            <th>alkalom sorszáma</th>
                            <th>esemény</th>
                            <th>időpont</th>
                            <th>munkalap id</th>
                        </tr>`;

                    services.forEach(service => {
                        html += `<tr>
                        <td>${service.lognumber}</td>
                        <td>${service.event}</td>
                        <td>${service.car.registered}</td>
                        <td>${service.document_id}</td>
                    </tr>`;
                    });

                    html += "</table>";
                    $("#carServices").html(html);
                    $("#carServices").removeClass('hidden');
                });
            });

            // Keresés
            $("#searchClient").click(function() {
                let name = $("#searchName").val();
                let card = $("#searchCard").val();
                $("#searchError").text("");

                $.get(`/api/search-client?name=${name}&idcard=${card}`, function(data) {
                    $("#searchResult").html(
                        `<p>
                            ID: ${data.id}, 
                            Név:  
                            <a href="#" class="client-link underline text-blue-600" data-id="${data.id}">${data.name}</a>,
                            Okmány: ${data.idcard}, 
                            Autók: ${data.car_count} db, 
                            Szerviznaplók: ${data.service_count}
                        </p>`
                        );
                }).fail(function(xhr) {
                    $("#searchError").text(xhr.responseJSON.error);
                });
            });

            loadClients();
        });
    </script>
@endsection