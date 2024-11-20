import axios from "axios";
const url = 'http://127.0.0.1:8000/'

export const getTasksService = async () => {
    return (await axios.get(`${url}admin/tasks`))
}
export const getUsersService = async () => {
    return (await axios.get(`${url}admin/users`))
}

export const editUserService = async ({id, name, email}: {id: number, name: string, email: string}) => {
    return (await axios.post(`${url}admin/update_user`, {
        id: id,
        name: name,
        email: email
    }))
}
export const deleteUserService = async (id: number) => {
    return (await axios.post(`${url}admin/delete_user`, {
        id: id,
    }))
}

export const promoteUserService = async (id: number) => {
    return (await axios.post(`${url}admin/promote_user`, {
        id: id,
    }))
}


