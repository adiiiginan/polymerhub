<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: sans-serif;
            padding: 2em;
        }

        .form-group {
            margin-bottom: 1em;
        }

        .form-control {
            width: 300px;
        }
    </style>
</head>

<body>

    <h1>Location Demo</h1>

    <div class="form-group">
        <label for="country-select">Country</label><br>
        <select id="country-select" class="form-control"></select>
    </div>

    <div class="form-group">
        <label for="state-select">State</label><br>
        <select id="state-select" class="form-control"></select>
    </div>

    <div class="form-group">
        <label for="city-select">City</label><br>
        <select id="city-select" class="form-control"></select>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 untuk negara
            $('#country-select').select2({
                placeholder: 'Select a country',
                ajax: {
                    url: '/api/locations/countries',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.country_name,
                                    id: item.iso2
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            // Ketika negara dipilih
            $('#country-select').on('select2:select', function(e) {
                var countryCode = e.params.data.id;
                $('#state-select').val(null).trigger('change').empty();
                $('#city-select').val(null).trigger('change').empty();

                // Cek apakah negara punya state
                $.get('/api/locations/' + countryCode + '/has-states', function(data) {
                    if (data.has_states) {
                        $('#state-select').prop('disabled', false);
                        // Inisialisasi Select2 untuk state
                        $('#state-select').select2({
                            placeholder: 'Select a state',
                            ajax: {
                                url: '/api/locations/' + countryCode + '/states',
                                dataType: 'json',
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return {
                                                text: item.state_name,
                                                id: item.state_code
                                            }
                                        })
                                    };
                                }
                            }
                        });
                    } else {
                        $('#state-select').prop('disabled', true);
                        // Langsung inisialisasi city
                        initializeCitySelect(countryCode);
                    }
                });
            });

            // Ketika state dipilih
            $('#state-select').on('select2:select', function(e) {
                var countryCode = $('#country-select').val();
                var stateCode = e.params.data.id;
                $('#city-select').val(null).trigger('change').empty();
                initializeCitySelect(countryCode, stateCode);
            });

            function initializeCitySelect(countryCode, stateCode = null) {
                var url = '/api/locations/' + countryCode;
                if (stateCode) {
                    url += '/' + stateCode;
                }
                url += '/cities';

                $('#city-select').select2({
                    placeholder: 'Select a city',
                    ajax: {
                        url: url,
                        dataType: 'json',
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.name,
                                        id: item.name
                                    }
                                })
                            };
                        }
                    }
                });
            }
        });
    </script>

</body>

</html>
