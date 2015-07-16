<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#5898D8">

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <link rel="icon" type="image/vnd.microsoft.icon" href="https://s3-eu-west-1.amazonaws.com/static.arthurguy.co.uk/images/ArthurGuy.ico" />

    <title>Home Dashboard</title>

    <link href="/css/dashboard.css" rel="stylesheet">

</head>
<body>

<div class="container-fluid">

    <div id="dashboard">



        <section class="forecast">

            <div v-if="!forecastAvailable">
                <div class="forecast-loading">
                    Loading weather forecast...
                </div>
            </div>

            <div v-if="forecastAvailable">

                <h1 class="daySummary" v-text="forecast.dayWeather.daySummary"></h1>

                <div class="keyForecast">
                    <weather-icon width="200" height="200" icon="{{ forecast.futureForecast.icon }}"></weather-icon>

                    <span class="primaryTemp"><temperature value="{{ forecast.temperature }}"></temperature></span>

                    <span class="tempRange"><temperature value="{{ forecast.dayWeather.dayMinTemperature }}"></temperature> - <temperature value="{{ forecast.dayWeather.dayMaxTemperature }}"></temperature></span>

                    <span v-text="forecast.condition" class="condition"></span>
                </div>

            </div>

        </section>



        <div class="room-list">

            <room v-repeat="rooms"></room>

        </div>



        <h2 style="text-align: center;">
            <span v-class="hidden : !location.home">
                <span class="glyphicon glyphicon-home"></span> Home
            </span>
            <span v-class="hidden : location.home">
                <span class="glyphicons glyphicons-person-running"></span> Away
            </span>
        </h2>

        <!--
        <div v-class="refreshing : loading" class="refresh">
            <span class="button-icon glyphicons glyphicons-refresh" v-on="click: refreshData"></span>
        </div>
        -->

        <!--
        <span class="glyphicons glyphicons-riflescope"></span>Geolocate
        <span class="glyphicons glyphicons-ban"></span>device error
        -->

        <small>
            Local: <?php echo json_decode(file_get_contents(base_path('resources/assets/versions.json')), true)['dashboard']; ?> |
            Remote: <span>{{ meta.version }}</span> |
            Last Update: <span>{{ lastDataUpdate | simple-date }}</span>
            <br />
            Forecast: <span v-if="localLocation">Local</span><span v-if="!localLocation">Home</span> |
            <span class="glyphicons glyphicons-riflescope" v-on="click: fetchCordinates"></span>
            <span>{{ latitude }}</span>
            <span>{{ longitude }}</span>
        </small>

        <div class="msg-container" v-class="show: showMessage" v-class="app-loaded: appLoaded">
            <div class="msg">{{ messageText }}</div>
        </div>

    </div>

<script id="room-template" type="x-template">
    <div v-class="heater-on : heater && heater.on, cooling-on : fan && fan.on, auto-mode : mode == 'auto'" class="room">

        <div class="heading">

            <span class="name">
                {{ name }}
                <span v-if="hasWarning" class="glyphicons glyphicons-warning-sign" title="No Updates since {{ last_updated }}" data-toggle="tooltip" data-placement="top" style="color:#FF7100;font-size: 16px;padding-top: 5px;"></span>
            </span>
            <span class="primaryTemp"><temperature value="{{ temperature }}"></temperature> | {{ humidity }}%</span>

        </div>

        <span class="condition hidden">{{ condition }}</span>

        <span class="heater-status">
            <span v-if="home">Maintaining <temperature value="{{ target_temperature }}"></temperature></span>
            <span v-if="!home">Maintaining Away <temperature value="{{ away_temperature }}"></temperature></span>
        </span>

        <div class="action-row">

            <div v-if="heater || cooler || fan" class="action">
                <span v-class="button-active : mode == 'auto'" v-on="click: modeToggle" class="button-icon glyphicons glyphicons-repeat mode-toggle"></span>
            </div>

            <div v-if="heater" class="action">
                <span v-class="button-active : heater.on" v-on="click: heaterToggle" class="button-icon glyphicons glyphicons-heat device-toggle"></span>
            </div>

            <div v-if="fan" class="action">
                <span v-class="button-active : fan.on" v-on="click: fanToggle" class="button-icon glyphicons glyphicons-snowflake device-toggle"></span>
            </div>

            <div v-if="lighting" class="action">
                <span v-class="button-active : lighting.on" v-on="click: lightingToggle" class="button-icon glyphicons glyphicons-lightbulb device-toggle"></span>
            </div>

        </div>

        <div class="action-detail" v-if="lighting && lighting.on">
            Lighting
            <colour name="light-color" on-update="{{updateLightingColour}}" raw-colour="{{lighting.value}}"></colour>
        </div>

    </div>
</script>

</div>


<script src="/js/dashboard.js"></script>

</body>
</html>