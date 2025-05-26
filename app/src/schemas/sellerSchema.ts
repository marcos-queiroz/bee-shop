import { z } from 'zod'

export const sellerSchema = z.object({
  name: z.string().min(2, 'O nome é obrigatório'),
  email: z.string().email('E-mail inválido'),
})

export type SellerFormData = z.infer<typeof sellerSchema>
