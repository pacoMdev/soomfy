const required = (value, args, { field }) => {
    if (!value) {
        return `The ${field} field is required.`
    }

    return true
}

const email = (value, args, { field }) => {
    if (!value) {
        return `The ${field} field is required.`
    }

    return true
}
// TRANSACTIONS

const userBuyer_id = (value, args, { field }) => {
    if (!value){
        return `El ${field} es necesario`;
    }
    return true;
} 

const userSeller_id = (value, args, { field }) => {
    if (!value){
        return `El ${field} es necesario`;
    }
    return true;
} 

const product_id = (value, args, { field }) => {
    if (!value){
        return `El ${field} es necesario`;
    }
    return true;
} 

const initialPrice = (value, args, { field }) => {
    if (!value){
        return `El ${field} es necesario`;
    }
    return true;
} 
const finalPrice = (value, args, { field }) => {
    if (!value){
        return `El ${field} es necesario`;
    }
    return true;
} 
const isRegated = (value, args, { field }) => {
    if (!value){
        return `El ${field} es necesario`;
    }
    return true;
} 
const isToSend = (value, args, { field }) => {
    if (!value){
        return `El ${field} es necesario`;
    }
    return true;
} 

const min = (value, [limit], { field }) => {
    if (!value || !value.length) {
        return true
    }

    if (value.length < limit) {
        return `The ${field} must be at least ${limit} characters.`
    }

    return true
}

export { 
    required, 
    min,
    email,
    userBuyer_id,
    userSeller_id,
    product_id,
    initialPrice,
    finalPrice,
    isRegated,
    isToSend,
 }
