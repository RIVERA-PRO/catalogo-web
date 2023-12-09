import React, { useState, useEffect } from 'react';
import axios from 'axios';
import './Carrito.css';

import Swal from 'sweetalert2';
import { Link as Anchor } from "react-router-dom";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faTrash } from '@fortawesome/free-solid-svg-icons';
import LoadingCarrito from '../LoadingCarrito/LoadingCarrito';
import wpp from '../../images/wpp.png'
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
export default function Carrito() {
    const [products, setProducts] = useState([]);
    const [showSpiral, setShowSpiral] = useState(true);
    const [totalPrice, setTotalPrice] = useState(0);
    const [userData, setUserData] = useState(null);

    const updateUserData = () => {
        const user = localStorage.getItem('user');
        if (user) {
            setUserData(JSON.parse(user));
        }
    };

    const obtenerProductosEnCarrito = () => {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        console.log('Carrito:', carrito);
        setProducts(carrito);
        setShowSpiral(false);

        // Calcula el precio total sumando los precios de todos los productos en el carrito
        const total = carrito.reduce((total, item) => total + item.precio * item.cantidad, 0);
        setTotalPrice(total);
    };

    useEffect(() => {
        updateUserData();
        obtenerProductosEnCarrito();
    }, []);

    // Función para convertir datos binarios a URL de datos
    const convertirDatosBinariosAUrl = (imagenData) => {
        return `data:image/png;base64,${imagenData}`;
    };
    const calcularTotal = (carrito) => {
        return carrito.reduce((total, item) => total + item.precio * item.cantidad, 0);
    };
    const eliminarProducto = (id) => {
        const nuevoCarrito = products.filter((item) => item.id !== id);
        setProducts(nuevoCarrito);
        toast.success('Producto eliminado');
        // Actualiza el carrito en el almacenamiento local
        localStorage.setItem('carrito', JSON.stringify(nuevoCarrito));
        const total = calcularTotal(nuevoCarrito);
        setTotalPrice(total);
    };


    const handleWhatsappMessage = () => {
        const phoneNumber = '3875683101'; // Reemplaza con el número de teléfono al que deseas enviar el mensaje

        // Crea una lista de detalles completos de los productos en el carrito
        const cartDetails = products.map((item) => (
            `${item.categoria}\n\n *${item.nombre}* - \n\n Precio: $${item.precio?.toLocaleString()}\n\n Cantidad: $${item.cantidad} \n\n Producto:(${`https://www.faugetdigital.shop/producto/${item.id}/${item.nombre.replace(/\s+/g, '-')}`})
---`
        ));
        const message = `¡Hola! 🌟 Estoy interesado en los siguientes productos en mi carrito:
    
    ${cartDetails.join('')}
Total:$ ${totalPrice?.toLocaleString()}
¿Podrías proporcionarme más información o ayudarme con la compra? 🛍️`;

        const whatsappUrl = `https://api.whatsapp.com/send?phone=${phoneNumber}&text=${encodeURIComponent(message)}`;

        window.open(whatsappUrl, '_blank');
    };





    return (
        <div className='carrito_contain'>
            <ToastContainer />
            <div className='fondoPage'>
                <Anchor to={`/`}>Inicio</Anchor>
                |
                <Anchor to={`/carrito`}>Carrito</Anchor>
            </div>
            <div className='carrito'>
                {showSpiral && <LoadingCarrito />}
                {!showSpiral && products.length > 0 ? (
                    <div className='carritoGrid'>
                        <div className='cardsCarrito'>
                            {products.map((item) => (
                                <div key={item.id} className='cardCarrito'>
                                    <img src={convertirDatosBinariosAUrl(item?.imagen)} alt="" />
                                    <div className='cardCarritoText'>
                                        <h3>{item.nombre}</h3>
                                        <div className='deFlexver'>
                                            <p>Cantidad: {item.cantidad}</p>
                                            <h4>$ {item.precio?.toLocaleString()}</h4>
                                        </div>
                                        <div className='deFlexver'>
                                            <Anchor to={`/producto/${item.id}/${item.nombre.replace(/\s+/g, '-')}`}>Ver producto</Anchor>
                                            <button onClick={() => eliminarProducto(item.id)}>
                                                <FontAwesomeIcon icon={faTrash} />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                        {products && (
                            <div className='card_pago'>
                                <h2>Resumen de Compra</h2>
                                <div className='deFlexTotal'>
                                    <h3>Total: </h3>
                                    <h3>$ {totalPrice?.toLocaleString()}</h3>
                                </div>
                                <button className="agregar">Finalizar Compra</button>
                                <button className="consultar" onClick={handleWhatsappMessage}>
                                    Consultar al
                                    <img src={wpp} alt="" />
                                </button>
                            </div>
                        )}
                    </div>
                ) : (
                    <div className='no_hay'>
                        <p>No hay productos en el carrito.</p>
                    </div>
                )}
            </div>
        </div>
    );
}
