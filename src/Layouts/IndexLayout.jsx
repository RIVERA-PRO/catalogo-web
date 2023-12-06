import React from 'react'
import Header from '../Pages/Header/Header'


import Hero from '../Components/Hero/Hero'
import Footer from '../Components/Footer/Footer'

import NavbarMobile from '../Components/NavbarMobile/NavbarMobile'

import TitleSection from '../Components/TitleSection/TitleSection'
import Productos from '../Components/Productos/Productos'
export default function IndexLayout() {
    return (
        <div >
            <Header />
            <Hero />

            <TitleSection section="Productos" link="products" />
            <Productos />
            <TitleSection section="Destacados" link="products" />
            <Productos />
            <Footer />
            <NavbarMobile />

        </div>
    )
}
