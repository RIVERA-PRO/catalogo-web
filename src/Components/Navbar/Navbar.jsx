import React, { useState, useEffect } from 'react'
import './Navbar.css'

import { Link as Anchor, useNavigate, useLocation } from "react-router-dom";
import InputSearch from '../InputSerach/InputSearchs'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faHome, faChartPie, faShoppingCart } from '@fortawesome/free-solid-svg-icons';
import logo from '../../images/logo.png'
import logonav from '../../images/logonav.png'

export default function Navbar() {

    const [isOpen, setIsOpen] = useState(false)
    const [scrolled, setScrolled] = useState(false);
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




    const handleScroll = () => {
        const offset = window.scrollY;
        if (offset > 70) {
            setScrolled(true);
        } else {
            setScrolled(false);
        }
    };
    useEffect(() => {
        window.addEventListener("scroll", handleScroll);
        return () => {
            window.removeEventListener("scroll", handleScroll);
        };
    }, []);


    return (
        <header className={scrolled ? "navbar scrolled " : "navbar"}>
            <nav >


                <Anchor to={`/`} className='logo'>
                    <img src={logo} alt="logo" />

                </Anchor>
                <Anchor to={`/`} className='logo-nav2'>
                    <img src={logonav} alt="logo" />
                </Anchor>


                <div className={`nav_items ${isOpen && "open"}`} >

                    <div className='deFlexClose'>
                        <Anchor to={`/`} className='logo-nav'>
                            <img src={logonav} alt="logo" />
                        </Anchor>


                    </div>

                    <div className='enlaces'>

                        <Anchor to={`/`} ><FontAwesomeIcon icon={faHome} /> Inico</Anchor>
                        <Anchor to={`/nosotros`} ><FontAwesomeIcon icon={faHome} /> Nosotros</Anchor>
                        <Anchor to={`/contacto`} ><FontAwesomeIcon icon={faHome} /> Contacto</Anchor>
                        <Anchor to={`/productos`} ><FontAwesomeIcon icon={faHome} /> Productos</Anchor>

                    </div>


                </div>


                <div className='enlaces2'>
                    <Anchor to={`/`} >Inico</Anchor>
                    <Anchor to={`/nosotros`} >Nosotros</Anchor>
                    <Anchor to={`/contacto`} >Contacto</Anchor>
                    <Anchor to={`/productos`} >Productos</Anchor>

                    {userData?.is_admin === true && <Anchor to={`/dashboard`} >  Dashboard</Anchor>}
                </div>

                <div className='deFlexnav'>

                    <InputSearch />

                    <Anchor to={`/carrito`}>
                        <FontAwesomeIcon icon={faShoppingCart} className="search-icon" />

                    </Anchor>
                    <div className={`nav_toggle  ${isOpen && "open"}`} onClick={() => setIsOpen(!isOpen)}>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>




                </div>







            </nav>


        </header>
    );
}
