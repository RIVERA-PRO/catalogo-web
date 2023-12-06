import React, { useState, useEffect, useRef } from 'react';

import { Link as Anchor } from "react-router-dom";
import axios from 'axios';
import './LoadingProductos.css';

import SwiperCore, { Navigation, Pagination, Autoplay } from 'swiper';
import { Swiper, SwiperSlide } from 'swiper/react';

import 'swiper/swiper-bundle.css';
export default function LoadingProductos() {

    const swiperRef = useRef(null);
    SwiperCore.use([Navigation, Pagination, Autoplay]);




    return (
        <div className='productosHome'>



            <Swiper
                effect={'coverflow'}
                grabCursor={true}
                loop={true}
                slidesPerView={'auto'}
                coverflowEffect={{ rotate: 0, stretch: 0, depth: 100, modifier: 2.5 }}
                navigation={{ nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' }}
                autoplay={{ delay: 3000 }} // Cambia el valor de 'delay' según tus preferencias

                onSwiper={(swiper) => {
                    console.log(swiper);
                    swiperRef.current = swiper;
                }}
                id={"swiper_container_scroll"}
            >
                <SwiperSlide id={"swiperCardScroll"} >
                    <div className='cardloadin'>

                    </div>
                </SwiperSlide>


                <SwiperSlide id={"swiperCardScroll"} >
                    <div className='cardloadin'>

                    </div>
                </SwiperSlide>
                <SwiperSlide id={"swiperCardScroll"} >
                    <div className='cardloadin'>

                    </div>
                </SwiperSlide>
                <SwiperSlide id={"swiperCardScroll"} >
                    <div className='cardloadin'>

                    </div>
                </SwiperSlide>
                <SwiperSlide id={"swiperCardScroll"} >
                    <div className='cardloadin'>

                    </div>
                </SwiperSlide>


            </Swiper>


        </div>
    );
}
