import React, { useState, useEffect } from "react";
import "./InputSearchMobile.css";

import { Link } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faSignOutAlt, faSearch } from '@fortawesome/free-solid-svg-icons'; // Importa el icono de búsqueda
import axios from 'axios';

export default function InputSearchMobile() {
    const [searchTerm, setSearchTerm] = useState("");
    const [filteredProductos, setFilteredProductos] = useState([]);
    const [showResults, setShowResults] = useState(false);
    const [noResults, setNoResults] = useState(false);
    const [productos, setProductos] = useState([]);

    useEffect(() => {
        // Obtener datos de la base de datos utilizando PHP
        fetch('/catalogoApi.php')  // Update the URL to your PHP endpoint
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                setProductos(data);
            })
            .catch(error => {
                console.error('Error al obtener los productos:', error);
            });
    }, []);

    const handleButtonClick = (producto) => {
        console.log(producto);
    };

    const handleSearch = (event) => {
        const searchTerm = event.target.value;
        setSearchTerm(searchTerm);

        const results = productos.filter((producto) => {
            return (
                producto.nombre.toLowerCase().includes(searchTerm.toLowerCase()) ||
                producto.categoria.toLowerCase().includes(searchTerm.toLowerCase())
            );
        });
        setFilteredProductos(results);
        setShowResults(searchTerm !== "");
        setNoResults(searchTerm !== "" && results.length === 0);
    };

    const [modalOpen, setModalOpen] = useState(false);

    const openModal = () => {
        setModalOpen(true);
    };

    const closeModal = () => {
        setModalOpen(false);
    };
    return (
        <div className="InputSearchMobileContain">




            <div id="enlaceMobile" onClick={openModal}  >

                <FontAwesomeIcon icon={faSearch} className="inputSearchMobile" />
                <span>Buscar</span>
            </div>

            {modalOpen && (
                <div className="modalSearchMobileContain">
                    <div className="modalSearchMobile">
                        <span className="close" onClick={closeModal}>X</span>
                        <div className="inputSearschMobile" >


                            <FontAwesomeIcon icon={faSearch} className="search-icon2" />

                            <input
                                type="text"
                                placeholder="Buscar..."

                                value={searchTerm}
                                onChange={handleSearch}
                                className="input"
                            />
                            {showResults && (
                                <div className="modalMobile">
                                    {filteredProductos.map((producto) => (
                                        <div key={producto.id}>
                                            <button className="btn-music" onClick={() => handleButtonClick(producto)}></button>
                                            <Link to={`/producto/${producto.id}/${producto.nombre.replace(/\s+/g, '-')}`} onClick={closeModal}>

                                                <p>{producto.nombre} - {producto.categoria}</p>
                                            </Link>
                                        </div>
                                    ))}
                                    {noResults && <p>No se encontraron resultados.</p>}
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
