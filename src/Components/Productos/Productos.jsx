import React, { useState, useEffect, useRef } from 'react';
import LoadingProductos from '../LoadingProductos/LoadingProductos';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faShoppingCart } from '@fortawesome/free-solid-svg-icons';
import SwiperCore, { Navigation, Pagination, Autoplay } from 'swiper';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Link as Anchor } from "react-router-dom";
import './Productos.css';
import 'swiper/swiper-bundle.css';
const Productos = () => {
    const [catalogos, setCatalogos] = useState([]);
    const [loading, setLoading] = useState(true);
    const swiperRef = useRef(null);
    useEffect(() => {
        // Obtener datos de la base de datos utilizando PHP
        fetch('/all_catalogos.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                setCatalogos(data);
                setLoading(false); // Cambiar el estado de carga a falso cuando se obtienen los datos
            })
            .catch(error => {
                console.error('Error al obtener datos:', error);
                setLoading(false); // Cambiar el estado de carga a falso en caso de error
            });
    }, []); // Se ejecuta solo una vez al montar el componente

    return (
        <div className='productosHome'>

            {loading ? (
                <LoadingProductos />
            ) : (

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

                    {catalogos.map(catalogo => (

                        <SwiperSlide key={catalogo?.id} id={"swiperCardScroll"}>
                            <div className='cardScroll'>


                                <Anchor to={`producto/${catalogo.id}/${catalogo.nombre.replace(/\s+/g, '-')}`}>
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

                                    >
                                        {[catalogo?.imagen, catalogo?.imagen2, catalogo?.imagen3, catalogo?.imagen4].map((image, index) => (
                                            image && (
                                                <SwiperSlide key={index} >

                                                    <img
                                                        src={`data:image/png;base64,${image}`}
                                                        alt={`Imagen del catálogo ${catalogo?.nombre}`}
                                                    />
                                                </SwiperSlide>
                                            )
                                        ))}
                                    </Swiper>
                                </Anchor>
                                <div className='cardText'>
                                    <h3>{catalogo?.nombre}</h3>
                                    <p>{catalogo?.descripcion} </p>

                                    <div className='deFlexbtns'>
                                        <h4>$ {catalogo?.precio?.toLocaleString()}</h4>
                                        <button className="cart" >
                                            <FontAwesomeIcon icon={faShoppingCart} />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </SwiperSlide>

                    ))}
                </Swiper>

            )}
        </div>
    );
};

export default Productos;
