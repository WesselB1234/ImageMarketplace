import router from "@/router"
import axios from '@/utils/axios.js'
import { useErrorHandlingStore } from '@/stores/errorHandlingStore'

async function getImageById(imageId) {
    
    const errorHandlingStore = useErrorHandlingStore()
    
    try {
        const response = await axios.get('/images/' + imageId)
        return response.data
    }
    catch (ex){
        if (ex.response){
            errorHandlingStore.errorMessage = ex.response.data.message
            router.push('/portfolio')
        }
        else {
            useErrorHandlingStore.errorMessage = ex.message
        }
    }
}

export { getImageById }