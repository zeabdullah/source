import { HttpInterceptorFn } from '@angular/common/http'
import { BASE_URL } from '~/shared/constants/http.constants'

export const baseInterceptor: HttpInterceptorFn = (req, next) => {
    const modifiedReq = req.clone({
        url: BASE_URL + req.urlWithParams,
        withCredentials: true,
        setHeaders: {
            Accept: 'application/json',
        },
    })
    return next(modifiedReq)
}
