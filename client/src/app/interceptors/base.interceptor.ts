import { HttpInterceptorFn } from '@angular/common/http'

export const baseInterceptor: HttpInterceptorFn = (req, next) => {
    const modifiedReq = req.clone({
        url: 'http://localhost:8000' + req.urlWithParams,
        withCredentials: true,
        setHeaders: {
            Accept: 'application/json',
        },
    })
    return next(modifiedReq)
}
