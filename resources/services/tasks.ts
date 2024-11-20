import axios from "axios";
const url = 'http://127.0.0.1:8000/'
export const getTasks = async (user_id: number) => {
    return (await axios.get(`${url}tasks/${user_id}`))
}

export const createTaskService = async ({title, description}: {title: string, description: string}) => {
    return (await axios.post(`${url}tasks/create`, {
        title: title,
        description: description
    }))
}

export const editTaskService = async ({id ,title, description}: {id: number, title: string, description: string}) => {
    console.log(id, title, description);
    return (await axios.put(`${url}tasks/update`, {
        id: id,
        title: title,
        description: description
    }))
}

export const doneTaskService = async (id: number) => {
    return (await axios.post(`${url}tasks/done`, {
        id: id,
    }))
}
