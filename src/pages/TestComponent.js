import React, { useEffect, useState } from "react";
import TestCrud from "../api/TestCrud";
import { Link, useNavigate, useParams } from "react-router-dom";

const TestComponent = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [state, setState] = useState({
    name: "",
    description: "",
  });

  useEffect(() => {
    if (id) {
        getData(id);
    }
  }, [id]);

  const getData = async (id) => {
    const res = await TestCrud.show(id);
      setState(res);
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setState({
        ...state,
        [name]: value,
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    id ? (state.id = id) : "";

    const res = await TestCrud.save(state);
    if (res) {
        navigate("/list");
    }
  };


  return (
    <div className='m-auto h-100'>
      <div className='bg-white p-4'>
        <Link to='/list' className="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow mb-4">
          List
        </Link>
        <form class="w-full max-w-lg mx-auto" onSubmit={handleSubmit}>
          <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">
                Name
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="name" type="text" name='name' value={state.name} onChange={handleChange}/>
            </div>
            <div class="w-full px-3">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">
                Description
              </label>
              <textarea class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="description" type="text" name='description' value={state.description} onChange={handleChange}/>
            </div>
          </div>
          <button type='submit' className='px-3 py-2 bg-gray-700 text-white rounded-md'>
            Submit
          </button>
        </form>
      </div>
    </div>
  )
}

export default TestComponent