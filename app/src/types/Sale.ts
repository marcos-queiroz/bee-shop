import type { Seller } from './Seller'

export interface Sale {
  id: number
  seller_id: number
  amount: string | number
  commission: string | number
  sale_date: string
  status?: string
  created_at?: string
  updated_at?: string
  deleted_at?: string | null
  seller?: Seller
}