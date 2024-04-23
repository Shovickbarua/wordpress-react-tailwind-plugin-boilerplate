import React, { useEffect, useState } from "react";
import TestCrud from "../api/TestCrud";
import { Link } from "react-router-dom";

const TestComponentList = () => {
    const [state, setState] = useState([]);

    useEffect(() => {
        getData();
    }, []);

    const getData = async () => {
        const res = await TestCrud.index();
            setState(res);
    };

    const deleteData = async (id) => {
        const confirmMsg = confirm("Are you sure!");
        if (confirmMsg) {
            const res = await TestCrud.delete(id);
            // console.log("res", res);
            if (res) {
                getData();
            }
        }
    };
  return (
    <div className='mt-10'>
        <div className=" max-w-7xl m-auto">
        <div className="flex justify-between ">
            <div className="text-lg text-gray-500">
                <h1>All Datas</h1>
            </div>
            <div>
                <Link to='/' className="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow mb-4">
                    Add
                </Link>
            </div>
        </div>
            <div className="flex flex-col">
                <div className="overflow-x-auto shadow-md sm:rounded-lg">
                    <div className="inline-block min-w-full align-middle">
                        <div className="overflow-hidden ">
                            <table className="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-700">
                                <thead className="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" className="p-4">
                                            <div className="flex items-center">
                                                <input id="checkbox-all" type="checkbox" className="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
                                                <label for="checkbox-all" className="sr-only">checkbox</label>
                                            </div>
                                        </th>
                                        <th scope="col" className="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                            Name
                                        </th>
                                        <th scope="col" className="p-4">
                                            <span className="sr-only">Action</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                {state.map((data, index) => (
                                    <tr className="hover:bg-gray-100 dark:hover:bg-gray-700" key={index}>
                                        <td className="p-4 w-4">
                                            <div className="flex items-center">
                                                <input id="checkbox-table-1" type="checkbox" className="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
                                                <label for="checkbox-table-1" className="sr-only">checkbox</label>
                                            </div>
                                        </td>
                                        <td className="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{data.name}</td>
                                        <td className="py-8 px-6 text-sm font-medium float-right whitespace-nowrap flex my-auto">
                                            <Link to={'/edit/'+ data.id}  className="text-blue-600 dark:text-blue-500 hover:underline text-2xl">Edit</Link>
                                            <button onClick={() => deleteData(data.id)} className="ml-2 text-blue-600 dark:text-blue-500 hover:underline text-2xl">Delete</button>
                                        </td>
                                    </tr>
                                ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  )
}

export default TestComponentList