@extends('layouts.BaseView')

@section('main-content')
<div id="map"></div>
<div class="card">
    <div class="card-body">
        <form id="controls">
            @if(1 === $experts->count() && Auth::id() == $experts->first()->id)
            <input type="hidden" name="expert" id="expert-select" value="{{Auth::id()}}">
            @else
            <select2 name="expert" id="expert-select" value="{{$selectedExpert ? $selectedExpert : $experts->first()->id}}">
                @foreach($experts as $expert)
                <option value="{{$expert->id}}">{{$expert->name}}</option>
                @endforeach
            </select2>
            @endif
            <div class="row my-4 align-items-center">
                <div class="col-md-3">
                <select2 id="address" value="{{$selectedCity ? $selectedCity : $cities->first()}}">
                        @foreach($cities as $city)
                            <option value="{{$city}}">{{$city}}</option>
                        @endforeach
                    </select2>
                    <span class="text-danger error text-sm my-1 d-none" id="address-error">
                        Bitte Stadt eingeben
                    </span>
                    <span class="text-danger error text-sm my-1 d-none" id="no-results-error">
                        Keine Ergebnisse gefunden
                    </span>
                </div>
                <div class="col-md-3">
                    <select2 id="input_radius_standort" value="500" placeholder="Bitte Radius angeben"
                        style="width: 10%; display: inline;">
                        <option value="500">500 Meter</option>
                        <option value="750">750 Meter</option>
                        <option value="1000">1 KM</option>
                        <option value="1500">1,5 KM</option>
                        <option value="2000">2 KM</option>
                        <option value="2500">2,5 KM</option>
                        <option value="3000">3 KM</option>
                        <option value="4000">4 KM</option>
                        <option value="5000">5 KM</option>
                        <option value="6000">6 KM</option>
                        <option value="7000">7 KM</option>
                        <option value="8000">8 KM</option>
                        <option value="9000">9 KM</option>
                        <option value="10000">10 KM</option>
                        <option value="12500">12,5 KM</option>
                        <option value="15000">15 KM</option>
                        <option value="17500">17,5 KM</option>
                        <option value="20000">20 KM</option>
                        <option value="30000">30 KM</option>
                        <option value="40000">40 KM</option>
                        <option value="50000">50 KM</option>
                        <option value="60000">60 KM</option>
                        <option value="70000">70 KM</option>
                        <option value="80000">80 KM</option>
                        <option value="90000">90 KM</option>
                        <option value="100000">100 KM</option>
                        <option value="200000">200 KM</option>
                        <option value="300000">300 KM</option>
                        <option value="400000">400 KM</option>
                        <option value="500000">500 KM</option>
                    </select2>
                </div>
                <div class="col-md-3">
                    <select2 id="input_type" placeholder="Select type" value="{{$categories->keys()->first()}}" style="width: 10%; display: inline;">
                        @foreach ($categories as $slug => $category)
                        <option value="{{$slug}}">{{$category}}</option>
                        @endforeach
                    </select2>
                </div>
                <div class="col-md-3">
                    <a id="start_import" class="btn text-white btn-primary" onclick=initialize()>Leads Importieren</a>
                    <a id="reload_page" class="btn text-white btn-primary" style="display: none"
                        onclick="window.location.reload()">Refresh</a>
                </div>
            </div>
        </form>
        <div class="text-center d-none" id="spinner">
            <b-spinner style="width: 5rem; height: 5rem;" label="Large Spinner"></b-spinner>
        </div>
        <table class="table table-sm" id="myTable">
        </table>
    </div>
</div>

@endsection

@prepend('footer-scripts')
<script type="text/javascript"
    src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyDSBODFxCAqqrAP2nhqlDKHTb9hYNxq1hs"></script>
@endprepend

@prepend('footer-scripts')
    <script>
        var categories = {!! $categories->keys()->toJson() !!};
    </script>
@endprepend

@push('footer-scripts')
<script>
    var map;

        var request;
        var service;
        var markers = [];
        var placeIds = [];

        var form = document.getElementById('controls');
        form.addEventListener('change', () => {
            form.querySelectorAll('.error').forEach((element) => {
                element.classList.add('d-none');
            } )
        })

        async function initialize(e) {
            setQueryStringParameter('selected_expert', document.getElementById('expert-select').value);
            setQueryStringParameter('selected_city', document.getElementById('address').value)
            document.getElementById('start_import').style.display = 'none';
            document.getElementById('reload_page').style.display = 'inline-block';
            document.getElementById('myTable').innerHTML= '';
            document.getElementById('spinner').classList.toggle('d-none')
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 13,
                center: {lat: -34.397, lng: 150.644}
            });

            var address = document.getElementById('address').value;
            let location = await getAddress(address);
            console.log(location);
            let geoString = location.toString();

            var stringLength = geoString.length - 1;
            var newSubstring = geoString.substring(1, stringLength);
            var spl = newSubstring.split(',');
            var numLtd = parseFloat(spl[0]);
            var numLng = parseFloat(spl[1]);
            console.log("Longitude" + numLng + " latitude: " + numLtd);

            var center = new google.maps.LatLng(numLtd, numLng);
            map = new google.maps.Map(document.getElementById('map'), {
                center: center,
                zoom: 13
            });

            var radius = getSliderValue();
            var type = getEstablishmentValue();

            if (type === undefined || type === null || type === '') {
                console.log("Type of establishment is not viable!!!");
                return;
            } else {
                console.log("Looking for places with type" + type);
            }

            request = {
                location: center,
                radius: radius,
                types: [type],
                fields: ['name', 'formatted_phone_number', 'geometry', 'website', 'international_phone_number', 'types'],
            };

            let service = new google.maps.places.PlacesService(map)

            var elementCounter = 0;
            var indexCounter = 0;
            service.nearbySearch(request, (results, status, pagetoken) => {
                results.forEach(element => {
                    elementCounter++;
                })

                results = results.filter(result => {
                    return categories.some(cat => result.types.includes(cat));
                })

                //displays all locations in a table
                //displayInTable(results, indexCounter);
                saveToArray(results);
                //checks for more than 20 total results -> maximum of 60
                if (pagetoken.hasNextPage) {
                    indexCounter = elementCounter;
                    pagetoken.nextPage();
                }else{
                    document.getElementById('spinner').classList.toggle('d-none');
                    getDetailsToPlaceId();
                }
            });
        }

        function setQueryStringParameter(name, value) {
            const params = new URLSearchParams(window.location.search);
            params.set(name, value);
            window.history.replaceState({}, "", decodeURIComponent(`${window.location.pathname}?${params}`));
        }

        function saveToArray(results) {
            results.forEach(element => {
                console.log(element.place_id);
                placeIds.push(element.place_id);
            })
        }

        const getAddress = address => {
            return new Promise((resolve, reject) => {
                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({address: address, region: 'de'}, (results, status) => {
                    if (status === 'OK') {
                        resolve(results[0].geometry.location);
                    }
                    if (status === 'INVALID_REQUEST') {
                        document.getElementById('address-error').classList.remove('d-none');
                        document.getElementById('spinner').classList.toggle('d-none');
                        reject(status);
                    }
                    if (status === 'ZERO_RESULTS') {
                        document.getElementById('no-results-error').classList.remove('d-none');
                        document.getElementById('spinner').classList.toggle('d-none');
                        reject(status);
                    }
                    else {
                        reject(status);
                    }
                });
            });
        };

        function logNewResult() {
            var totalCounter = 0;
            placeIds.forEach(element => {
                console.log(element);
                totalCounter++;
            })
            console.log(totalCounter);
        }

        function getDetailsToPlaceId() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 13,
                center: {lat: -34.397, lng: 150.644}
            });
            var service = new google.maps.places.PlacesService(map);
            placeIds.forEach( function(element, index) {
                var request = {
                    placeId: element,
                    fields: ['name', 'website', 'international_phone_number', 'place_id', 'types', 'geometry','formatted_address', 'address_components']
                };
                window.setTimeout(()=>{
                    service.getDetails(request, callbackForDetails);
                }, 500 * (index + 1));
            })
        }

        function callbackForDetails(place, status) {
            if (status == google.maps.places.PlacesServiceStatus.OK) {
                axios.post('/api/callcenter/store-lead', {
                    "place": place,
                    "expert": document.getElementById('expert-select').value
                }).then(() => {
                    displayInTable2(place);
                }).catch((error)=>{
                    displayInTable2(place, true, error.response.data.message);
                })
            }
            else{
                console.log(status);
            }
        }

        function logJsonToPost() {
            if (JsonToPost.length > 0) {
                JsonToPost.forEach(element => {
                    displayInTable2(element, JsonToPost.length);
                })
            } else {
                console.log("No locations were found!");
            }

        }
        var counter = 1;
        function displayInTable2(place, isError = false, message = '') {
            var rowClass = isError ? 'table-danger' : 'table-succsess';
            var table = document.getElementById("myTable");
            var row = table.insertRow();
            row.classList.add(rowClass);
            row.setAttribute('data-toggle', 'tooltip');
            row.setAttribute('data-placement', 'top');
            row.title= message;
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            cell1.innerHTML = `${counter++}`;
            cell2.innerHTML = place.name;
            cell3.innerHTML = place.formatted_address;
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
        }
        function getSliderValue(){
            var slider = document.getElementById("input_radius_standort");
            return slider.value;
        }

        var establishment = ["accounting", "airport", "amusement_park", "aquarium", "art_gallery", "atm", "bakery", "bank", "bar", "beauty_salon", "bicycle_store", "book_store", "bowling_alley", "bus_station", "cafe", "campground", "car_dealer", "car_rental", "car_repair", "car_wash", "casino", "cemetery", "church", "city_hall", "clothing_store", "convenience_store", "courthouse", "dentist", "department_store", "doctor", "drugstore", "electrician", "electronics_store", "embassy", "fire_station", "florist", "funeral_home", "furniture_store", "gas_station", "grocery_or_supermarket", "gym", "hair_care", "hardware_store", "hindu_temple", "home_goods_store", "hospital", "insurance_agency", "jewelry_store", "laundry", "lawyer", "library", "light_rail_station", "liquor_store", "local_government_office", "locksmith", "lodging", "meal_delivery", "meal_takeaway", "mosque", "movie_rental", "movie_theater", "moving_company", "museum", "night_club", "painter", "park", "parking", "pet_store", "pharmacy", "physiotherapist", "plumber", "police", "post_office", "primary_school", "real_estate_agency", "restaurant", "roofing_contractor", "rv_park", "school", "secondary_school", "shoe_store", "shopping_mall", "spa", "stadium", "storage", "store", "subway_station", "supermarket", "synagogue", "taxi_stand", "tourist_attraction", "train_station", "transit_station", "travel_agency", "university", "veterinary_care", "zoo"];

        function getEstablishmentValue(){
            var text = document.getElementById("input_type");
            return text.value;
        }
</script>
@endpush