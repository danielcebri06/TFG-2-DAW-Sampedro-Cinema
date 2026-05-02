import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule } from '@angular/common/http';
import { AppRoutingModule } from './app-routing-module';
import { App } from './app';

import { CarteleraComponent } from './cartelera/cartelera';
import { ReservaEntradasComponent } from './reserva-entradas/reserva-entradas';
import { TestApiComponent } from './test-api/test-api';
import { AdminDashboard } from './admin/admin-dashboard/admin-dashboard';
import { AdminPeliculas } from './admin/admin-peliculas/admin-peliculas';
import { AdminSesiones } from './admin/admin-sesiones/admin-sesiones';
import { AdminSalas } from './admin/admin-salas/admin-salas';

@NgModule({
  declarations: [
    App,
    CarteleraComponent,
    ReservaEntradasComponent,
    TestApiComponent,
    AdminDashboard,
    AdminPeliculas,
    AdminSesiones,
    AdminSalas,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule, // Permite las peticiones a la API
  ],
  bootstrap: [App],
})
export class AppModule {}
