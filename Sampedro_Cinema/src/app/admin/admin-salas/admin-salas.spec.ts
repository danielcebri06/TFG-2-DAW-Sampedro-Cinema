import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminSalas } from './admin-salas';

describe('AdminSalas', () => {
  let component: AdminSalas;
  let fixture: ComponentFixture<AdminSalas>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [AdminSalas],
    }).compileComponents();

    fixture = TestBed.createComponent(AdminSalas);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
