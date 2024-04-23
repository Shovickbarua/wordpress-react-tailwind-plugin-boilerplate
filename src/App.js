import * as React from "react";
import * as ReactDOM from "react-dom";
import { createBrowserRouter, createHashRouter, RouterProvider } from "react-router-dom";
import TestComponent from "./pages/TestComponent";
import TestComponentList from "./pages/TestComponentList";

const router = createHashRouter([
    { path: "/", element: <TestComponent /> },
    { path: "/edit/:id", element: <TestComponent /> },
    { path: "/list", element: <TestComponentList /> },
    
]);

ReactDOM.createRoot(document.getElementById("test-app")).render(
    <React.StrictMode>
        <RouterProvider router={router} />
    </React.StrictMode>
);

// Use the given below code if you don't want to use router

// document.addEventListener( 'DOMContentLoaded', function() {
//     var element = document.getElementById( 'test-app' );
//     if( typeof element !== 'undefined' && element !== null ) {
//         ReactDOM.render( <TestComponent />, document.getElementById( 'test-app' ) );
//     }
// })