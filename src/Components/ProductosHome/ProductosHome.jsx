import React, { useState, useEffect, useRef } from 'react';
import LoadingProductos from '../LoadingProductos/LoadingProductos';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faShoppingCart } from '@fortawesome/free-solid-svg-icons';
import SwiperCore, { Navigation, Pagination, Autoplay } from 'swiper';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Link as Anchor } from "react-router-dom";
import './ProductosHome.css';
import 'swiper/swiper-bundle.css';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
const ProductosHome = () => {
    const [catalogos, setCatalogos] = useState([]);
    const [loading, setLoading] = useState(true);
    const swiperRef = useRef(null);
    useEffect(() => {
        // Obtener datos de la base de datos utilizando PHP
        fetch('/catalogoApi.php')
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

    const agregarAlCarrito = (id) => {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const productoEnCarrito = carrito.find((item) => item.id === id);

        if (productoEnCarrito) {
            // Si el producto ya está en el carrito, incrementa la cantidad
            productoEnCarrito.cantidad += 1;
        } else {
            // Si el producto no está en el carrito, agrégalo
            carrito.push({
                id,
                nombre: catalogos.find((item) => item.id === id)?.nombre,
                categoria: catalogos.find((item) => item.id === id)?.categoria,
                precio: catalogos.find((item) => item.id === id)?.precio,
                imagen: catalogos.find((item) => item.id === id)?.imagen,
                cantidad: 1, // Nueva propiedad para la cantidad
            });
        }

        // Actualiza el carrito en el almacenamiento local
        localStorage.setItem('carrito', JSON.stringify(carrito));

        // Puedes mostrar una alerta o mensaje de éxito aquí si lo deseas
        toast.success('Agregado al carrito');
    };
    return (
        <div className='productosHome'>
            <ToastContainer />
            {loading ? (
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
                    <SwiperSlide id={"swiperCardScroll2"} >
                        <div className='cardloadin2'>

                        </div>
                    </SwiperSlide>


                    <SwiperSlide id={"swiperCardScroll2"} >
                        <div className='cardloadin2'>

                        </div>
                    </SwiperSlide>
                    <SwiperSlide id={"swiperCardScroll2"} >
                        <div className='cardloadin2'>

                        </div>
                    </SwiperSlide>
                    <SwiperSlide id={"swiperCardScroll2"} >
                        <div className='cardloadin2'>

                        </div>
                    </SwiperSlide>
                    <SwiperSlide id={"swiperCardScroll2"} >
                        <div className='cardloadin2'>

                        </div>
                    </SwiperSlide>


                </Swiper>
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

                    {catalogos.reverse().map(catalogo => (

                        <SwiperSlide key={catalogo?.id} id={"swiperCardScroll2"}>
                            <div className='cardScroll2'>


                                <Anchor to={`producto/${catalogo.id}/${catalogo.nombre.replace(/\s+/g, '-')}`} >
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
                                        id={"swiperCardScrollImg2"}
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
                                <div className='cardText2'>
                                    <h3>{catalogo?.nombre}</h3>
                                    <p>{catalogo?.descripcion} </p>

                                    <div className='deFlexbtns2'>
                                        <h4>$ {catalogo?.precio?.toLocaleString()}</h4>
                                        <button className='cart' onClick={() => agregarAlCarrito(catalogo.id)}>
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

export default ProductosHome;
