export function initials(name: string) : string {
    let result: string, parts: string[];
    parts = name.split(' ');
    if (parts.length > 1) {
        result = parts.reduce((total: string, el: string, ind: number) => {
            // get first two words' initial letters
            if (el)
                return ind < 2 ? total + (el.substring(0, 1).toUpperCase()) : total;
            else
                return total;
        },'');
    } else if(parts.length === 1){ // no spaces -> first two letters then
        result = name.substring(0,2).toUpperCase();
    } else {
        result = name; // no way
    }
    return result;
}
