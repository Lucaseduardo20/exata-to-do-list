import axios from "axios";

export const getTasks = async (user_id: number) => {
    return (await axios.get(`http://127.0.0.1:8000/tasks/${user_id}`))
}
