import React, { useEffect, useRef, useState } from 'react';
import './Hero.css';
import img1 from '../../images/1.png';
import img2 from '../../images/2.png';
import img3 from '../../images/3.png';
import img4 from '../../images/4.png';
import SwiperCore, { Navigation, Pagination, Autoplay } from 'swiper/core';
import { Swiper, SwiperSlide } from 'swiper/react';
import 'swiper/swiper-bundle.css';
import HeroLoading from '../HeroLoading/HeroLoading';
SwiperCore.use([Navigation, Pagination, Autoplay]);

export default function Hero() {
    useEffect(() => {
        window.scrollTo(0, 0);
    }, []);

    const swiperRef = useRef(null);
    const [imagesLoaded, setImagesLoaded] = useState(false);

    const handleImagesLoad = () => {
        setImagesLoaded(true);
    };

    return (
        <div className='heroContain'>
            {!imagesLoaded && <HeroLoading />}
            <Swiper
                effect={'coverflow'}
                grabCursor={true}
                loop={true}
                slidesPerView={'auto'}
                coverflowEffect={{ rotate: 0, stretch: 0, depth: 100, modifier: 2.5 }}
                navigation={{ nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' }}
                autoplay={{ delay: 3000 }}
                pagination={{ clickable: true }}
                onSwiper={(swiper) => {
                    console.log(swiper);
                    swiperRef.current = swiper;
                }}
                id={"swiper_container_img"}
            >
                <SwiperSlide id={"swiperImg"} >
                    <img src={img1} alt="" onLoad={handleImagesLoad} />
                </SwiperSlide>
                <SwiperSlide id={"swiperImg"} >
                    <img src={img2} alt="" onLoad={handleImagesLoad} />
                </SwiperSlide>

                <SwiperSlide id={"swiperImg"} >
                    <img src={img3} alt="" onLoad={handleImagesLoad} />
                </SwiperSlide>
                <SwiperSlide id={"swiperImg"} >
                    <img src={img4} alt="" onLoad={handleImagesLoad} />
                </SwiperSlide>
            </Swiper>
        </div>
    );
}
