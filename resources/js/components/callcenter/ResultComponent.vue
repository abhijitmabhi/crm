<template>
    <div>
        <div stlye="background: lightgrey" class="p-4">
            <div class="d-flex justify-content-between">
                <div class="ml-4 d-flex flex-column flex-grow-1">
                    <img class="d-block mx-auto" :src="smiley" alt />
                    <p class="text-center h1 text-dark">{{message}}</p>
                    <button class="btn btn-success mt-4" @click="emitClick">nächster Lead</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        level: Number
    },
    methods: {
        emitClick() {
            this.$emit("nextLead");
        }
    },
    computed: {
        smiley() {
            let lvl = this.level != 5 ? this.level : 1;
            return `/img/callcenter/${lvl}.png`;
        },
        message() {
            switch (this.level) {
                case 1:
                    return "super gemacht!";
                    break;
                case 2:
                    return "sehr gut!";
                    break;
                case 3:
                    return "ok";
                    break;
                case 4:
                    return "Mitmachen ist alles";
                case 5:
                    return "";
                    break;
            }
        }
    }
};
</script>
