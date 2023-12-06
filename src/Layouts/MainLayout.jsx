import React, { useState, useEffect } from 'react';
import Header from '../Pages/Header/Header';
import { Outlet } from 'react-router-dom';
import Footer from '../Components/Footer/Footer';
import NavbarMobile from '../Components/NavbarMobile/NavbarMobile';

export default function MainLayout() {
    const [windowWidth, setWindowWidth] = useState(window.innerWidth);

    useEffect(() => {
        const handleResize = () => {
            setWindowWidth(window.innerWidth);
        };

        window.addEventListener('resize', handleResize);

        return () => {
            window.removeEventListener('resize', handleResize);
        };
    }, []);

    // Define el ancho límite para mostrar el encabezado
    const breakpoint = 1020;

    return (
        <div>
            {windowWidth >= breakpoint && <Header />}
            <Outlet />
            <Footer />
            <NavbarMobile />
        </div>
    );
}
