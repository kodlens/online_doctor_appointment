<template>
    <section>
        
        <b-carousel
            :progress="true">
            <b-carousel-item v-for="(carousel, i) in carousels" :key="i"
                class="carousel-slide"
                :style="`background-image: url('${carousel.img}');`">
                
                <div class="slide-container">
                    <div class="slide-text-container">
                        <h1 class="slide-text-title">{{carousel.title}}</h1>
                        <h1 class="slide-text-subtitle">{{ carousel.subtitle }}</h1>
                    </div>
                </div>

            </b-carousel-item>
        </b-carousel>
    </section>
</template>

<script>
export default {
    data() {
        return {
            arrow: true,
            arrowBoth: false,
            arrowHover: false,
            iconPack: 'mdi',
            iconPrev: 'arrow-left',
            iconNext: 'arrow-right',
            iconSize: '',
            carousels: [
                { title: 'Book Your Clinic Appointments Online', 
                    img: '/img/slide-img-1.jpg' ,
                    subtitle: 'Convenient, Fast, and Reliable'
                },
                { title: 'Expert Care for Your Health', 
                    img: '/img/slide-img-2.jpg' ,
                    subtitle: 'Your Trusted Clinic for Medical Appointments'
                },
                { title: 'Experience Exceptional Healthcare', 
                    img: '/img/slide-img-3.jpg' ,
                    subtitle: 'Compassionate and Skilled Medical Professionals'
                },
               
            ]
        }
    }
}
</script>


<style scoped>
.carousel-slide {
  height: 450px; /* Set the desired height of the carousel slide */
  background-size: cover; /* Ensure the image covers the entire slide */
  background-position: center; /* Center the image within the slide */
}

.slide-container{
    height: 400px;

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.slide-text-container{
    padding: 30px;
    background-color: rgba(0, 0, 0, 0.7);
    text-align: center;
}

.slide-text-title{
    font-weight: bold;
    font-size: 1.4em;
    color: white;
}
.slide-text-subtitle{
    font-weight: bold;
    font-size: 1em;
    color: white;
}

</style>