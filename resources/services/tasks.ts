import axios from "axios";

export const getTasks = async (user_id: number) => {
    return (await axios.get(`http://127.0.0.1:8000/tasks/${user_id}`))
}

export const createTaskService = async ({title, description}: {title: string, description: string}) => {
    return (await axios.post(`http://127.0.0.1:8000/tasks/create`, {
        title: title,
        description: description
    }))
}
