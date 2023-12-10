import React, { useState, useEffect, useRef } from 'react';
import SwiperCore, { Navigation, Pagination, Autoplay } from 'swiper';
import { Swiper, SwiperSlide } from 'swiper/react';
import axios from 'axios';
import 'swiper/swiper-bundle.css';
import { Link as Anchor } from "react-router-dom";
import './ProductosPage.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faSearch, faShoppingCart } from '@fortawesome/free-solid-svg-icons';
import Swal from 'sweetalert2';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import LoadingProductos from '../LoadingProductos/LoadingProductos';
export default function ProductosPage() {
    const [catalogos, setCatalogos] = useState([]);
    const [showSpiral, setShowSpiral] = useState(true);
    const swiperRef = useRef(null);
    const [searchQuery, setSearchQuery] = useState('');
    const [selectedCategories, setSelectedCategories] = useState([]);
    const [selectedCategory, setSelectedCategory] = useState('');

    SwiperCore.use([Navigation, Pagination, Autoplay]);

    useEffect(() => {
        fetch('/catalogoApi.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                setCatalogos(data);
                setShowSpiral(false); // Cambiar el estado de carga a falso cuando se obtienen los datos
            })
            .catch(error => {
                console.error('Error al obtener datos:', error);
                setShowSpiral(false); // Cambiar el estado de carga a falso en caso de error
            });
    }, []);

    const [priceRange, setPriceRange] = useState({
        min: 10000,
        max: 3000000,
    });

    const filteredProducts = catalogos.filter((item) =>
        item.nombre.toLowerCase().includes(searchQuery.toLowerCase()) &&
        item.precio >= priceRange.min &&
        item.precio <= priceRange.max &&
        (selectedCategories.length === 0 || selectedCategories.includes(item.categoria))
    );

    const allCategories = [...new Set(catalogos.map((item) => item.categoria))];

    const handleCategoryChange = (categoria) => {

        if (selectedCategories.includes(categoria)) {
            setSelectedCategories(selectedCategories.filter((c) => c !== categoria));
        } else {
            setSelectedCategories([...selectedCategories, categoria]);
        }
    };

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
        <div className='carrito_contain'>
            <ToastContainer />
            <div className='fondoPage'>
                <Anchor to={`/`}>Inicio</Anchor>
                |
                <Anchor to={`/products`}>Productos</Anchor>
            </div>
            {showSpiral &&
                <LoadingProductos />
            }
            {!showSpiral && (
                <div className='gridProducts'>
                    <div className='filtros'>
                        <fieldset className='filterproduct'>
                            <FontAwesomeIcon icon={faSearch} className="search-icon2" />
                            <input
                                type="text"
                                placeholder="Buscar por título"
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                            />
                        </fieldset>
                        <fieldset className='filtroPrecio'>
                            <h3>Filtrar por precio:</h3>
                            <input
                                type="range"
                                min="10000"
                                max="3000000"
                                value={priceRange.min}
                                onChange={(e) =>
                                    setPriceRange({ ...priceRange, min: e.target.value })
                                }
                            />
                            <input
                                type="range"
                                min="10000"
                                max="3000000"
                                value={priceRange.max}
                                onChange={(e) =>
                                    setPriceRange({ ...priceRange, max: e.target.value })
                                }
                            />
                            <div className="price-range-labels">
                                <span>Min {priceRange.min} / </span>
                                <span>Max {priceRange.max}</span>
                            </div>
                        </fieldset>
                        <div className='filtroCategoria' >
                            <h3>Filtrar por categoría:</h3>
                            {allCategories.map((category) => (

                                <label key={category} className={selectedCategories.includes(category) ? 'selectedCategory' : ''}>
                                    <input
                                        type="checkbox"
                                        value={category}
                                        checked={selectedCategories.includes(category)}
                                        onChange={() => handleCategoryChange(category)}
                                    />
                                    {category}
                                </label>


                            ))}



                        </div>
                    </div>


                    <div className='productosFiltrados'>
                        {filteredProducts.length > 0 ? (
                            filteredProducts.map((item) => (
                                <div className='cardProduct'>
                                    <SwiperSlide id={""}>
                                        <div className='cardScroll'>


                                            <Anchor to={`producto/${item.id}/${item.nombre.replace(/\s+/g, '-')}`} >
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
                                                    {[item?.imagen, item?.imagen2, item?.imagen3, item?.imagen4].map((image, index) => (
                                                        image && (
                                                            <SwiperSlide key={index} >
                                                                <img src={`data:image/png;base64,${image}`}
                                                                    alt={`Imagen del catálogo ${item?.nombre}`} />
                                                            </SwiperSlide>
                                                        )
                                                    ))}
                                                </Swiper>
                                            </Anchor>
                                            <div className='cardText'>
                                                <h3>{item.nombre.slice(0, 25)}</h3>
                                                <p>{item.descripcion.slice(0, 50)}...</p>
                                                <div className='deFlexbtns'>
                                                    <h4>$ {item?.precio?.toLocaleString()}</h4>
                                                    <button className="cart" onClick={() => agregarAlCarrito(item.id)}>
                                                        <FontAwesomeIcon icon={faShoppingCart} />
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </SwiperSlide>
                                </div>
                            ))
                        ) : (
                            <div className='no_hay'>
                                <p>No se encontraron resultados.</p>
                            </div>
                        )}

                    </div>
                </div>
            )}
        </div>
    );
}
