import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CarteleraComponent } from './cartelera/cartelera';
import { ReservaEntradasComponent } from './reserva-entradas/reserva-entradas';
import { TestApiComponent } from './test-api/test-api';

const routes: Routes = [
  { path: '', component: CarteleraComponent },
  { path: 'cartelera', component: CarteleraComponent },
  { path: 'reservar/:id', component: ReservaEntradasComponent },
  { path: 'test', component: TestApiComponent },
  { path: '**', redirectTo: '' }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }