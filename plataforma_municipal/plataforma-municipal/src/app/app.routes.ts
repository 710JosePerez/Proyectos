import { Routes } from '@angular/router';
import { authGuard } from './guards/auth.guard';

export const routes: Routes = [
    {
        path: '',
        redirectTo: 'login',
        pathMatch: 'full'
    },
    {
        path: 'login',
        loadComponent: () =>
            import('./pages/login.component').then(m => m.LoginComponent)
    },
    {
        path: 'registro',
        loadComponent: () =>
            import('./pages/registro/registro.component').then(m => m.RegistroComponent)
    },
    {
        path: 'dashboard',
        loadComponent: () =>
            import('./pages/dashboard.component').then(m => m.DashboardComponent),
        canActivate: [authGuard]
    }
];
