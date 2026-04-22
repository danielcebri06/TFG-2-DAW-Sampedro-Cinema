import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ReservaEntradasComponent } from './reserva-entradas';

describe('ReservaEntradasComponent', () => {
  let component: ReservaEntradasComponent;
  let fixture: ComponentFixture<ReservaEntradasComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ReservaEntradasComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(ReservaEntradasComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});