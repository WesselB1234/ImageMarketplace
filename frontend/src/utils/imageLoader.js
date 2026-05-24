import router from "@/router"
import axios from '@/utils/axios.js'
import { useErrorHandlingStore } from '@/stores/errorHandlingStore'

async function getImageById(imageId, errorAlertRef) {
    
    const errorHandlingStore = useErrorHandlingStore()
    
    try {
        const response = await axios.get('/images/' + imageId)
        return response.data
    }
    catch (ex){
        if (ex.response){
            if (ex.response.status === 404) {
                
                if (errorAlertRef !== undefined) {
                    errorAlertRef.value.shutdown()
                }

                errorHandlingStore.errorMessage = ex.response.data.message
                router.push('/portfolio')
            }
            else {
                errorHandlingStore.errorMessage = ex.response.data.message
            }
        }
        else {
            useErrorHandlingStore.errorMessage = ex.message
        }
    }
}

export { getImageById }