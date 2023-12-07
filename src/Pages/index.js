import IndexLayout from "../Layouts/IndexLayout";
import MainLayout from "../Layouts/MainLayout";
import { createBrowserRouter } from "react-router-dom";

import Carrito from "../Components/Carrito/Carrito";
import PageDetail from '../Pages/PageDetail/PageDetail'
export const router = createBrowserRouter([
    {
        path: "/",
        element: <IndexLayout />,

    },
    {
        path: "/",
        element: <MainLayout />,
        children: [
            {
                path: "/carrito",
                element: <Carrito />,
            },

            {
                path: "/producto/:id/:nombre",
                element: <PageDetail />,
            },

        ],
    },


]);
