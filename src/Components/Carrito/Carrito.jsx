import React, { useState, useEffect } from 'react';
import axios from 'axios';
import './Carrito.css';

import Swal from 'sweetalert2';
import { Link as Anchor } from "react-router-dom";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faTrash } from '@fortawesome/free-solid-svg-icons';
import LoadingCarrito from '../LoadingCarrito/LoadingCarrito';
import wpp from '../../images/wpp.png'
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
    useEffect(() => {
        updateUserData();
    }, []);



    const handleWhatsappMessage = () => {
        const phoneNumber = '3875683101'; // Reemplaza con el número de teléfono al que deseas enviar el mensaje

        // Crea una lista de detalles completos de los productos en el carrito
        const cartDetails = products
            .filter((item) => item.user_id._id === userData?.user_id)
            .map((item) => (
                `
*${item.title}* - ${item.categoria}
Precio: $${item.price}
Producto:(${`https://tienda-virtual-jet.vercel.app/producto/${item.publicacion_id}`})
---`
            ));
        const message = `¡Hola! 🌟 Estoy interesado en los siguientes productos en mi carrito:
    
    ${cartDetails.join('')}
Total:$ ${totalPrice}
¿Podrías proporcionarme más información o ayudarme con la compra? 🛍️`;

        const whatsappUrl = `https://api.whatsapp.com/send?phone=${phoneNumber}&text=${encodeURIComponent(message)}`;

        window.open(whatsappUrl, '_blank');
    };



    return (
        <div className='carrito_contain'>
            <div className='fondoPage'>
                <Anchor to={`/`}>Inicio</Anchor>
                |
                <Anchor to={`/carrito`}>Carrito</Anchor>
            </div>
            <div className='carrito'>



                {showSpiral && <LoadingCarrito />}
                {!showSpiral && (
                    <div className='carritoGrid'>
                        <div className='cardsCarrito'>
                            {products
                                .filter((item) => item.user_id._id === userData?.user_id)
                                .map((item) => (


                                    <div key={item._id} className='cardCarrito'>
                                        <img src={item.cover_photo} alt="" />
                                        <div className='cardCarritoText'>
                                            <h3>{item.title}</h3>
                                            <div className='deFlexver'>
                                                <p>{item.categoria}</p>
                                                <h4>$ {item.price}</h4>
                                            </div>


                                            <div className='deFlexver'>
                                                <Anchor to={`/producto/${item.publicacion_id}`}>Ver producto</Anchor>
                                                <button >
                                                    <FontAwesomeIcon icon={faTrash} />
                                                </button>
                                            </div>
                                        </div>

                                    </div>




                                ))

                            }
                        </div>
                        {products
                            .filter((item) => item.user_id._id === userData?.user_id)

                            && <div className='card_pago'>
                                <h2>Resumen de Compra</h2>
                                <div className='deFlexTotal'>
                                    <h3>Total: </h3>
                                    <h3>$ {totalPrice}</h3>
                                </div>
                                <button className="agregar">Finalizar Compra</button>

                                <button className="consultar" onClick={handleWhatsappMessage}>
                                    Consultar al
                                    <img src={wpp} alt="" />
                                </button>


                            </div>
                        }

                    </div>
                )}


            </div>
        </div>
    );
}
