import React from 'react'
import Header from '../Pages/Header/Header'


import Hero from '../Components/Hero/Hero'
import Footer from '../Components/Footer/Footer'

import NavbarMobile from '../Components/NavbarMobile/NavbarMobile'
import InputSearchMobile2 from '../Components/InputSearchMobile2/InputSearchMobile2'
import TitleSection from '../Components/TitleSection/TitleSection'
import Productos from '../Components/Productos/Productos'
import ProductosHome from '../Components/ProductosHome/ProductosHome'
export default function IndexLayout() {
    return (
        <div >
            <NavbarMobile />
            <Header />
            <InputSearchMobile2 />
            <Hero />

            <TitleSection section="Productos" link="productos" />
            <Productos />
            <TitleSection section="Destacados" link="productos" />
            <ProductosHome />
            <Footer />


        </div>
    )
}
