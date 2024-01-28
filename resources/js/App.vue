<template>
    <div id="app" class="container py-5">
        <h1 class="text-center mb-4 fw-bold">
            Weather Forecast Nepal
        </h1>
        <div class="search-box d-flex justify-content-center">
            <input type="text" v-model="searchTerm" placeholder="City or Zip Code" @keyup.enter="getWeather"
                   class="form-control w-50">
            <button class="btn btn-primary" @click="getWeather">Search</button>
        </div>
        <div v-if="weatherData" class="py-5">
            <h4>{{ weatherData.location }}</h4>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12 mt-4 weather-items" v-for="(day, index) in weatherData.forecast" :key="index">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title m-0">
                                {{ day.date.date }} <br>
                                {{ day.date.day }}
                            </h5>
                            <a href="javascript:void(0)">
                                <img :src="day.condition.image" :alt="day.condition.text" class="card-img-top" :title="day.condition.text">
                            </a>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                Temperature: {{ day.temperature.avg }}°C<br>
                                Humidity: {{ day.humidity }}%<br>
                                Wind Speed: {{ day.wind }} km/h
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="d-flex justify-content-center align-items-center error-div">
                <div class="text-center">
                    <h2 class="fw-bold">{{ errorMessage }}</h2>
                </div>
            </div>
        </div>

        <div class="loading-screen" v-show="isLoading">
            <img src="../gif/loading.gif" alt="Loading..." class="loader-gif">
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            searchTerm: '',
            weatherData: null,
            errorMessage: "Start by typing a city name or zip code to check the weather in Nepal.",
            isLoading: false,
        };
    },
    methods: {
        getWeather() {
            if (!this.searchTerm) return;
            this.isLoading = true;
            const url = `/api/weather/forecast?${isNaN(this.searchTerm) ? `city=${this.searchTerm}` : `zipCode=${this.searchTerm}`}`;
            this.searchTerm = '';
            axios.get(url)
                .then((response) => {
                    this.weatherData = response.data.data;
                    this.isLoading = false;
                })
                .catch((error) => {
                    this.weatherData = null;
                    this.errorMessage = error.response.data.error;
                    this.isLoading = false;
                });
        },
    },
};
</script>
