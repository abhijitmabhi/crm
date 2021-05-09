<template>
    <b-card class="px-0 py-40" no-body>
        <b-card-body class="p-0 d-flex flex-column">
            <h3 class="px-40 lli-card-title">Reputation Management</h3>
            <div class="d-flex flex-grow-1 mg-1">
                <div class="w-50 d-flex flex-column border-right wd mg-40">
                    <div class="px-40 d-flex align-items-center border-bottom flex-grow-1 pd-0">
                        <div class="w-75">Gesamt Bewertungen</div>
                        <div class="w-25 text-center font-weight-bold">{{ totalRatings }}</div>
                    </div>
                    <div class="px-40 d-flex align-items-center border-bottom flex-grow-1 pd-0">
                        <div class="w-75">Neue Bewertungen</div>
                        <div class="w-25 text-center font-weight-bold">{{ newRatings }}</div>
                    </div>
                    <div class="px-40 d-flex align-items-center flex-grow-1 pd-0">
                        <div class="w-75">Durchschnitt Bewertungen</div>
                        <div class="w-25 text-center font-weight-bold">{{ avgRating }}</div>
                    </div>
                </div>
                <div class="w-50 px-40 d-flex flex-column fl">
                    <h4 class="mg-2">Top Bewertungen</h4>
                    <div class="d-flex flex-column flex-grow-1">
                        <div
                            v-for="(rating, i) in ratings"
                            :key="'rating_' + rating.name + '_' + i"
                            class="d-flex align-items-center flex-grow-1"
                        >
                            <div class="d-flex align-items-center" style="width: 60%">
                                <div class="h3 m-0" style="width: 33.33333333%">
                                    <i class="fab" :class="`fa-${rating.name}`"></i>
                                </div>
                                <div class="font-weight-bold" style="width: 33.33333333%">
                                    {{ rating.number }}
                                </div>
                                <div class="font-weight-bold" style="width: 33.33333333%">
                                    {{ rating.avg }}
                                </div>
                            </div>
                            <div style="width: 40%">
                                <b-rating
                                    v-model="rating.avg"
                                    readonly
                                    color: #fb9437
                                    variant="warning"
                                    precision="2"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-card-body>
    </b-card>
</template>

<script>
export default {
    data() {
        return {
            ratings: [
                { name: "google", number: 255, avg: 4.5, new: 2 },
                { name: "facebook", number: 24, avg: 4.4, new: 1 },
                { name: "yelp", number: 12, avg: 4.2, new: 0 },
                { name: "foursquare", number: 7, avg: 4, new: 0 },
            ],
        };
    },
    computed: {
        totalRatings() {
            return this.ratings.reduce((total, rating) => total + rating.number, 0);
        },
        newRatings() {
            return this.ratings.reduce((total, rating) => total + rating.new, 0);
        },
        avgRating() {
            return (
                this.ratings.reduce((total, rating) => total + rating.avg, 0) / this.ratings.length
            ).toFixed(1);
        },
    },
};
</script>
<style lang="scss" scoped>
.form-control {
    background: transparent;
}
.py-40 {
    padding-top: 40px;
    padding-bottom: 40px;
}
.px-40 {
    padding-left: 40px;
    padding-right: 40px;
}

@media (max-width:650px){
  .fl{
    float:left;
    position: absolute;
    margin-top:100px;
    width:100%!important;
  }

  .mg-1{
    margin-bottom: 200px;
  }

  .wd{
    width:100%!important;
  }
  .pd-0{
    padding-left:0px!important;
    padding-right:0px!important;
  }

  .mg-40{
    margin-left:40px;
    margin-right:40px;
    border-right:0px!important;
  }
  .form-control{
    padding:0px!important;
  }
}

@media (max-width:431px){
  .mg-2{
    margin-top:20px;
  }
}


</style>
