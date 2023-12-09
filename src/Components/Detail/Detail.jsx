import React, { useEffect, useState, useRef } from "react";
import { useParams } from "react-router-dom";
import './Detail.css';
import 'react-responsive-modal/styles.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faStar, faShoppingCart } from '@fortawesome/free-solid-svg-icons';
import LoadingDetail from "../LoadingDetail/LoadingDetail";
import SwiperCore, { Navigation, Pagination, Autoplay } from 'swiper';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Link as Anchor } from "react-router-dom";
import 'swiper/swiper-bundle.min.css';
import { Modal } from 'react-responsive-modal';
import Swal from 'sweetalert2';
import wpp from '../../images/wpp.png'
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
export default function Detail() {
    const { id } = useParams();
    const [loading, setLoading] = useState(true);
    const [producto, setProducto] = useState(null);
    const swiperRef = useRef(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [currentImageIndex, setCurrentImageIndex] = useState(0);

    SwiperCore.use([Navigation, Pagination, Autoplay]);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await fetch(`/catalogoApi.php`);  // Update the URL to your PHP endpoint
                if (response.ok) {
                    const data = await response.json();
                    const selectedProduct = data.find(product => product.id === parseInt(id));
                    setProducto(selectedProduct);
                    console.log(selectedProduct)
                    console.log(producto)
                    setLoading(false);
                } else {
                    console.error('Error al obtener los datos del catálogo:', response.status);
                    setLoading(false);
                }
            } catch (error) {
                console.error('Error al obtener los datos del catálogo:', error);
                setLoading(false);
            }
        };

        fetchData();
    }, [id]);

    useEffect(() => {
        window.scrollTo(0, 0);
    }, []);

    const images = [producto?.imagen, producto?.imagen2, producto?.imagen3, producto?.imagen4].filter(image => !!image);

    const openModal = (index) => {
        setCurrentImageIndex(index);
        setIsModalOpen(true);
    };

    const closeModal = () => {
        setIsModalOpen(false);
    };
    const handleWhatsappMessage = () => {
        const phoneNumber = '3875683101';
        const message = `¡Hola! 🌟 Estoy interesado en el producto:\n\n${producto?.categoria} \n\n ${producto?.nombre} \n\n $${producto?.precio?.toLocaleString()}.\n\nProducto: https://www.faugetdigital.shop/producto/${producto?.id}/${producto?.nombre.replace(/\s+/g, '-')}\n\n¿Podrías proporcionarme más información? 🤔`;
        const whatsappUrl = `https://api.whatsapp.com/send?phone=${phoneNumber}&text=${encodeURIComponent(message)}`;
        window.open(whatsappUrl, '_blank');
    };


    const agregarAlCarrito = () => {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const productoEnCarrito = carrito.find(item => item.id === producto.id);

        if (productoEnCarrito) {
            // Si el producto ya está en el carrito, incrementa la cantidad
            productoEnCarrito.cantidad += 1;
        } else {
            // Si el producto no está en el carrito, agrégalo
            carrito.push({
                id: producto.id,
                nombre: producto.nombre,
                categoria: producto.categoria,
                precio: producto.precio,
                imagen: producto.imagen,
                cantidad: 1, // Nueva propiedad para la cantidad
            });
        }

        // Actualiza el carrito en el almacenamiento local
        localStorage.setItem('carrito', JSON.stringify(carrito));

        // Puedes mostrar una alerta o mensaje de éxito aquí si lo deseas

        toast.success('Agregado al carrito');
    };
    return (
        <div className="contain-detail">
            <ToastContainer />
            <div className='fondoDetail'>

            </div>
            <div className="detail-contain">

                {loading ? (
                    <LoadingDetail />
                ) : producto ? (
                    <>

                        <div className="detail">
                            <div className="deFleximg">
                                <div className="imgDivs">
                                    {images.map((image, index) => (
                                        <img key={index} src={`data:image/png;base64,${image}`} alt="" onClick={() => openModal(index)} />
                                    ))}
                                </div>
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
                                    id={"swiperDetail"}
                                >
                                    {images.map((image, index) => (
                                        <SwiperSlide key={index} id={"swiperImgDetail"} onClick={() => openModal(index)}>
                                            <img key={index} src={`data:image/png;base64,${image}`} alt="" />
                                        </SwiperSlide>
                                    ))}
                                </Swiper>
                            </div>
                            <div className="deColumText">
                                <h1>{producto.nombre}</h1>
                                <Anchor to={`/products`}>
                                    <FontAwesomeIcon icon={faStar} /> Categoria / {producto.categoria}
                                </Anchor>
                                <h2>$ {producto.precio?.toLocaleString()}</h2>
                                <div className="btns_final">
                                    <button className="agregar" onClick={agregarAlCarrito} >
                                        Agregar al carrito
                                    </button>
                                    <button className="consultar" onClick={handleWhatsappMessage}>
                                        Consultar al
                                        <img src={wpp} alt="" />
                                    </button>
                                </div>
                                <div className="detalles">
                                    <div>
                                        <h3>Descripción</h3>
                                        <p className="description">{producto.descripcion}</p>
                                    </div>
                                </div>
                            </div>
                            <Modal open={isModalOpen} onClose={closeModal} center>
                                <img src={`data:image/png;base64,${images[currentImageIndex]}`} alt="" className="modal-image" />
                            </Modal>
                        </div>
                    </>
                ) : (
                    <div className="no_hay">
                        <p>No se encontró la publicación.</p>
                    </div>
                )}
            </div></div>
    );
}
